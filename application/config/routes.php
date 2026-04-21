<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
| BlogCMS URL routing.
|
| Public routes – anyone can visit
| Auth routes   – login / register / logout
| Post routes   – CRUD for posts (editors + admin)
| Category routes – CRUD (admin only)
| Admin routes  – dashboard, logs (admin only)
*/

// ── Default controller ────────────────────────────────────
$route['default_controller'] = 'Home';
$route['404_override']       = 'Home/not_found';
$route['translate_uri_dashes'] = FALSE;

// ── Auth ──────────────────────────────────────────────────
$route['login']    = 'Auth/login';
$route['register'] = 'Auth/register';
$route['logout']   = 'Auth/logout';

// ── Public blog ───────────────────────────────────────────
$route['blog']                     = 'Home/index';
$route['blog/category/(:segment)'] = 'Home/category/$1';
$route['blog/(:segment)']          = 'Home/post/$1';

// ── Posts (CRUD) ──────────────────────────────────────────
$route['posts']              = 'Posts/index';          // list
$route['posts/create']       = 'Posts/create';         // form
$route['posts/store']        = 'Posts/store';          // POST handler
$route['posts/edit/(:num)']  = 'Posts/edit/$1';        // edit form
$route['posts/update/(:num)']= 'Posts/update/$1';      // POST handler
$route['posts/delete/(:num)']= 'Posts/delete/$1';      // delete
$route['posts/view/(:num)']  = 'Posts/view/$1';        // detail

// ── Categories (CRUD, admin only) ─────────────────────────
$route['categories']               = 'Categories/index';
$route['categories/create']        = 'Categories/create';
$route['categories/store']         = 'Categories/store';
$route['categories/edit/(:num)']   = 'Categories/edit/$1';
$route['categories/update/(:num)'] = 'Categories/update/$1';
$route['categories/delete/(:num)'] = 'Categories/delete/$1';

// ── Admin ─────────────────────────────────────────────────
$route['admin']          = 'Admin/dashboard';
$route['admin/logs']     = 'Admin/logs';
$route['admin/users']    = 'Admin/users';
