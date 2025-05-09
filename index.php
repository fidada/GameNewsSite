<?php

use Auth\Auth;
use Parsidev\Jalali\jDate;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//configuration
define('BASE_PATH', __DIR__); 
define('CURRENT_DOMAIN', current_domain() . '/GameNewsSite/');
define('DB_HOST', 'localhost');
define('DB_NAME', 'news-project');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DISPLAY_ERROR', true);



//database
require_once 'database/DataBase.php';
require_once 'database/createDB.php';

//admin
require_once 'activities/Admin/Admin.php';
require_once 'activities/Admin/Category.php';
require_once 'activities/Admin/Dashboard.php';
require_once 'activities/Admin/Post.php';
require_once 'activities/Admin/User.php';
require_once 'activities/Admin/Comment.php';
require_once 'activities/Admin/Menu.php';
require_once 'activities/Admin/WebSetting.php';

//auth
require_once 'activities/Auth/Auth.php';

//Home
require_once "activities/Home.php";

//helpers 

// spl_autoload_register(function ($className) {
//     $path = BASE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
//     $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
//     include $path . $className . '.php'; 
// });
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class); // remove "App\" from class
    $path = 'activities/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($path)) {
        include $path;
    } else {
        echo "Autoloader failed: $class → $path<br>";
    }
});


// Fungsi ini secara otomatis memuat (autoload) class PHP saat dibutuhkan,
// dengan mengubah namespace menjadi path file dan langsung meng-include-nya.
// Dia ngilangin prefix 'App\' dan nyari file class-nya di folder 'activities/'.




//parsidev deleted
// function jalaliDate($date)
// {
//     return jDate::forge($date)->format('%A, %d %B %Y');
// }

// uri('admin/category', 'Admin\Category', 'index');
function uri($reservedUrl, $class, $method, $requestMethod = "GET")
{
    // current url array
    $currentUrl = explode('?', currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN, '', $currentUrl);
    $currentUrl = trim($currentUrl, '/');
    $currentUrlArray = explode('/', $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);


    // reserved url array
    $reservedUrl = trim($reservedUrl, '/');
    $reservedUrlArray = explode('/', $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);

    // admin/category/create
    // admin/category/create

    if(sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod){
            return false;
    }

    // admin/category/edit/2
    // admin/category/edit/{id}
    
 
    $parameters = [];
    for($key = 0; $key < sizeof($currentUrlArray); $key++)
    {
            if($reservedUrlArray[$key][0] == '{' && $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) - 1] == "}")
             {
                    array_push($parameters, $currentUrlArray[$key]);
            }
            elseif($currentUrlArray[$key] !== $reservedUrlArray[$key]){
                       // admin/category/delete/2
                    // admin/category/edit/{id}
    
                    return false;
            }
    }

    if(methodField() == 'POST')
    {
            $request = isset($_FILES) ? array_merge($_POST, $_FILES) : $_POST;
            $parameters = array_merge([$request], $parameters);
    }


    $object = new $class;
    call_user_func_array(array($object, $method), $parameters);
    // Category
    // $category = new Category;
    // $category->index();
    exit;
}

function asset($src)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $src = $domain . '/' . trim($src, '/ ');
    return $src;
}

function url($url)
{
    $domain = trim(CURRENT_DOMAIN, '/ ');
    $url = $domain . '/' . trim($url, '/ ');
    return $url;
} 

function protocol()
{
    return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
}

function current_domain()
{
    return protocol() . $_SERVER['HTTP_HOST'];
}

// echo current_domain();

function currentUrl()
{
    return current_domain() . $_SERVER['REQUEST_URI'];
}

// echo currentUrl();

function methodField()
{
    return $_SERVER['REQUEST_METHOD'];
}

// echo methodField();

function dd($vars)
{

    echo '<pre>';
    var_dump($vars);
    exit;

}

function displayError($displayError)
{

    if ($displayError) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }

}

displayError(DISPLAY_ERROR);

global $flashMessage;

if (isset($_SESSION['flash_message'])) {
    $flashMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

function flash($name, $value = null)
{

    if ($value === null) {

        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    } else {
        $_SESSION['flash_message'][$name] = $value;
    }

}

//dashboard
uri('admin', 'Admin\Dashboard', 'index');

//category
uri('admin/category', 'Admin\Category', 'index');
uri('admin/category/create', 'Admin\Category', 'create');
uri('admin/category/store', 'Admin\Category', 'store', "POST");
uri('admin/category/edit/{id}', 'Admin\Category', 'edit');
uri('admin/category/update/{id}', 'Admin\Category', 'update', "POST");
uri('admin/category/delete/{id}', 'Admin\Category', 'delete');

//post
uri('admin/post', 'Admin\Post', 'index');
uri('admin/post/create', 'Admin\Post', 'create');
uri('admin/post/store', 'Admin\Post', 'store', "POST");
uri('admin/post/edit/{id}', 'Admin\Post', 'edit');
uri('admin/post/update/{id}', 'Admin\Post', 'update', "POST");
uri('admin/post/delete/{id}', 'Admin\Post', 'delete');
uri('admin/post/breaking-news/{id}', 'Admin\Post', 'breakingNews');
uri('admin/post/selected/{id}', 'Admin\Post', 'selected');
uri('admin/post/change-status/{id}', 'Admin\Post', 'changeStatus');


// users
uri('admin/user', 'Admin\User', 'index');
uri('admin/user/edit/{id}', 'Admin\User', 'edit');
uri('admin/user/update/{id}', 'Admin\User', 'update', 'POST');
uri('admin/user/delete/{id}', 'Admin\User', 'delete');
uri('admin/user/permission/{id}', 'Admin\User', 'permission');

//comments
uri('admin/comment', 'Admin\Comment', 'index');
uri('admin/comment/change-status/{id}', 'Admin\Comment', 'changeStatus');

// menu
uri('admin/menu', 'Admin\Menu', 'index');
uri('admin/menu/create', 'Admin\Menu', 'create');
uri('admin/menu/store', 'Admin\Menu', 'store', 'POST');
uri('admin/menu/edit/{id}', 'Admin\Menu', 'edit');
uri('admin/menu/update/{id}', 'Admin\Menu', 'update', 'POST');
uri('admin/menu/delete/{id}', 'Admin\Menu', 'delete');

//web setting
uri('admin/web-setting', 'Admin\WebSetting', 'index');
uri('admin/web-setting/set', 'Admin\WebSetting', 'set');
uri('admin/web-setting/store', 'Admin\WebSetting', 'store', 'POST');

//Contact
uri('admin/contact', 'Admin\Contact', 'index');

// Auth
uri('register', 'Auth\Auth', 'register');
uri('register-aut', 'Auth\Auth', 'registerAut');
uri('register/store', 'Auth\Auth', 'registerStore', "POST");
uri('register/author', 'Auth\Auth', 'registerAuthor', "POST");

uri('login', 'Auth\Auth', 'login');
uri('check-login', 'Auth\Auth', 'checkLogin', "POST");
uri('logout', 'Auth\Auth', 'logout');


//user update
uri('user/edit/{id}', 'Auth\Auth', 'edit');
uri('user/update/{id}', 'Auth\Auth', 'update', 'POST');

  
//home
if (!isset($_SESSION['permission']) || $_SESSION['permission'] == 'user') {
    uri('/', 'App\Home', 'index');
}elseif (!isset($_SESSION['permission']) || $_SESSION['permission'] == 'admin'){
    uri('/', 'Admin\Dashboard', 'index');
}elseif (!isset($_SESSION['permission']) || $_SESSION['permission'] == 'author'){
    uri('/', 'App\Author', 'index');

}

uri('home', 'App\Home', 'index');
uri('show-post/{id}', 'App\Home', 'show');
uri('show-category/{id}', 'App\Home', 'category');
uri('comment-store', 'App\Home', 'commentStore', 'POST');
uri('profile', 'App\Home', 'profile');
uri('most-view', 'App\Home', 'mostViewed');
uri('about-us', 'App\Home', 'aboutUs');
uri('contact', 'App\Home', 'contact');
uri('contact-store', 'App\Home', 'contactStore', 'POST');
uri('user-delete/{id}', 'App\Home', 'delete');
uri('search', 'App\Home', 'search', 'GET');
uri('author-search', 'App\Author', 'search', 'GET');


//Author
uri('author', 'App\Author', 'index');

uri('author/post/create', 'App\Author', 'create');
uri('author/post/store', 'App\Author', 'store', "POST");
uri('author/post/edit/{id}', 'App\Author', 'edit');
uri('author/post/update/{id}', 'App\Author', 'update', "POST");
uri('author/post/delete/{id}', 'App\Author', 'delete');
uri('author/post/breaking-news/{id}', 'App\Author', 'breakingNews');
uri('author/post/selected/{id}', 'App\Author', 'selected');


echo '404 - not found';
exit;
