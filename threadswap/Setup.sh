#!/bin/bash
set -euo pipefail

if [[ ! -f "docker-compose.yml" ]]; then
    echo "docker-compose.yml not found. Please cd into the cloned threadswap repo first."
fi

echo "Updating apt package lists..."
apt-get update -qq

if command -v docker &>/dev/null; then
    echo "Docker already installed: $(docker --version)"
else
    echo "Installing Docker..."

    apt-get install -y -qq \
        ca-certificates \
        curl \
        gnupg \
        lsb-release

    install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/debian/gpg \
        | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    chmod a+r /etc/apt/keyrings/docker.gpg

    CODENAME=$(lsb_release -cs 2>/dev/null || echo "bookworm")
    if [[ "$CODENAME" == "trixie" || "$CODENAME" == "forky" ]]; then
        echo "Debian $CODENAME detected — using 'bookworm' Docker repo for compatibility."
        CODENAME="bookworm"
    fi

    echo \
      "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] \
      https://download.docker.com/linux/debian $CODENAME stable" \
      > /etc/apt/sources.list.d/docker.list

    apt-get update -qq
    apt-get install -y -qq \
        docker-ce \
        docker-ce-cli \
        containerd.io \
        docker-buildx-plugin \
        docker-compose-plugin

    echo "Docker installed: $(docker --version)"
fi

if ! systemctl is-active --quiet docker; then
    echo "Starting Docker daemon..."
    systemctl enable docker
    systemctl start docker
fi

if ! docker compose version &>/dev/null; then
    echo "docker compose (v2 plugin) not found after installation. Check apt logs."
fi
echo "Docker Compose: $(docker compose version)"

REAL_USER="${SUDO_USER:-}"
if [[ -n "$REAL_USER" && "$REAL_USER" != "root" ]]; then
    usermod -aG docker "$REAL_USER" && \
        echo "Added '$REAL_USER' to the docker group (re-login to apply)."
fi

echo "Ensuring writable directory structure..."
mkdir -p writable/{cache,logs,session,uploads,debugbar}
mkdir -p public/uploads
chmod -R 777 writable public/uploads

echo "Building and pulling Docker images (this may take a few minutes)..."
docker compose pull db        # pull MySQL image in parallel while we build
docker compose build app      # build the PHP/Apache image

echo "Starting ThreadSwap containers..."
docker compose up -d

echo "Waiting for MySQL to be ready..."
TRIES=0
MAX=30
until docker compose exec -T db mysqladmin ping -h localhost --silent 2>/dev/null; do
    TRIES=$((TRIES + 1))
    if [[ $TRIES -ge $MAX ]]; then
        echo "MySQL did not become healthy after ${MAX} attempts. Check: docker compose logs db"
    fi
    echo -n "."
    sleep 3
done
echo ""
echo "MySQL is ready."

echo "Running database migrations..."
docker compose exec -T app php spark migrate --all

echo "Seeding database (admin user + categories + sample data)..."
docker compose exec -T app php spark db:seed DatabaseSeeder

echo ""
echo -e "  Admin credentials:"
echo -e "    Email:    ${YELLOW}admin@threadswap.lt${NC}"
echo -e "    Password: ${YELLOW}Admin1234!${NC}"