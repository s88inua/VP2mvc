<?php
namespace MVC\MVC\Controller;

use MVC\App\Engine\MainController;
use MVC\MVC\Model\Photo;
use MVC\MVC\Model\User;
use Intervention\Image\ImageManagerStatic as Image;


class HomeController extends MainController
{

    public function index()
    {
        if (array_key_exists('user', $_SESSION) && null !== $_SESSION['user']) {
            $user = User::find($_SESSION['user']);
            if ($user->role !== 1) {
                $avatar = Photo::where('user_id', $_SESSION['user'])->where('current', 1)->first();
                $this->view->render('user.html.twig', ['user' => $user, 'avatar' => $avatar]);
            } else {
                if ($user->passChanged === 0) {
                    header('Location: /admin/password/request');
                } else {
                    $arrUsers = User::all();
                    $users = (isset($_SESSION['sort']))
                        ? ($_SESSION['sort'] === 'asc')
                            ? $arrUsers->sortBy('Age')
                            : $arrUsers->sortByDesc('Age')
                        : $arrUsers;
                    unset($_SESSION['sort']);
                    $this->view->render('admin.users.html.twig', ['users' => $users]);
                }
            }
        } else {
            if (array_key_exists('error', $_SESSION)) {
                $this->view->render('auth.html.twig', ['error' => $_SESSION['error']]);
                unset($_SESSION['error']);
            } else {
                $this->view->render('auth.html.twig');
            }
        }
    }

    public function userSort($sort)
    {
        $userCheck = User::find($_SESSION['user']);
        if ($userCheck && $userCheck->role === 1) {
            $_SESSION['sort'] = $sort;
            header('Location: /');
        } else {
            header('Location: /');
        }
    }

    public function login()
    {
        if (empty($_POST['login'])) {
            $_SESSION['error'] = 'Заполните поле логина';
            header('Location: /');
            return false;
        }

        if (empty($_POST['password'])) {
            $_SESSION['error'] = 'Заполните поле пароля';
            header('Location: /');
            return false;
        }

        $user = User::where('login', $_POST['login'])->first();

        if (!$user) {
            $_SESSION['error'] = 'Пользователь с таким логином не найден';
            header('Location: /');
            return false;
        }

        if (password_verify($_POST['password'], $user->password)) {
            $_SESSION['user'] = $user->id;
            if ($user->role === 1) {
                if ($user->passChanged === 0) {
                    header('Location: /admin/password/request');
                } else {
                    header('Location: /');
                }
            } else {
                header('Location: /');
            }
        } else {
            $_SESSION['error'] = 'Неверная пара Логин/Пароль';
            header('Location: /');
            return false;
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
    }

    public function formRegister()
    {
        if (array_key_exists('error', $_SESSION)) {
            $this->view->render('register.html.twig', ['error' => $_SESSION['error']]);
            unset($_SESSION['error']);
        } else {
            $this->view->render('register.html.twig');
        }
    }

    public function registerConfirm()
    {
        if (empty($_POST['login'])) {
            $_SESSION['error'] = 'Заполните поле логина';
            header('Location: /user/register/form');
            return false;
        }

        if (empty($_POST['password'])) {
            $_SESSION['error'] = 'Заполните поле пароля';
            header('Location: /user/register/form');
            return false;
        }

        if (empty($_POST['passwordConfirm'])) {
            $_SESSION['error'] = 'Заполните поле проверки пароля';
            header('Location: /user/register/form');
            return false;
        }

        $user = User::where('login', $_POST['login'])->first();

        if ($user) {
            $_SESSION['error'] = 'Пользователь с таким логином уже существует';
            header('Location: /user/register/form');
            return false;
        }

        if ($_POST['passwordConfirm'] !== $_POST['password'] ) {
            $_SESSION['error'] = 'Введенные пароли не совпадают';
            header('Location: /user/register/form');
            return false;
        }

        $newUser = new User();
        $newUser->login = strip_tags($_POST['login']);
        $newUser->password = password_hash(strip_tags($_POST['password']), PASSWORD_BCRYPT);
        $newUser->save();

        header('Location: /');
        return true;
    }

    public function infoUpdate()
    {
        $user = User::find($_POST['user']);
        $user->lastname    = strip_tags($_POST['lastName']);
        $user->firstname   = strip_tags($_POST['firstName']);
        $user->midname     = strip_tags($_POST['midName']);
        $user->birthdate   = strip_tags($_POST['birthDate']);
        $user->description = trim(strip_tags($_POST['description']));
        $user->save();

        header('Location: /');
    }

    public function upload()
    {
        $photo = $_FILES['photo'];
        if (!empty($photo['name'])) {
            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($photo['tmp_name']);

            $allowedExt = array('gif', 'png', 'jpg');
            $filename = $photo['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($detectedType, $allowedTypes) && in_array($ext, $allowedExt)) {
                $path = '/web/uploads/';
                $newPhoto = new Photo();
                $newPhoto->user_id = $_POST['user'];
                $newPhoto->path = $path;
                $newPhoto->save();

                $newPhoto->path .= $newPhoto->id;
                $newPhoto->save();
               $image = Image::make($photo['tmp_name'])->resize(200, 200)->save(__DIR__ . '/../../..' . $path . $newPhoto->id . '.gif');
                $user = User::find($_POST['user']);
                if ($user->role === 1) {\
                    header('Location: /admin/user/view/' . $_POST['user']);
                } else {
                    header('Location: /');
                }
            }
        }
    }

    public function selectForm()
    {
        $photos = Photo::where('user_id', $_SESSION['user'])->get();
        $this->view->render('photos.html.twig', ['photos' => $photos]);
    }

    public function avatar()
    {
        $photos = Photo::where('user_id', $_SESSION['user'])->update(['current' => 0]);
        $photo = Photo::find($_POST['avatar']);
        $photo->current = 1;
        $photo->save();

        header('Location: /');
    }

    public function passwordChange()
    {
        $userCheck = User::find($_SESSION['user']);
        if ($userCheck && $userCheck->role === 1) {
            if (empty($_POST['password'])) {
                $_SESSION['error'] = 'Заполните поле пароля';
                header('Location: /admin/password/request');
                return false;
            }

            if (empty($_POST['passwordConfirm'])) {
                $_SESSION['error'] = 'Заполните поле проверки пароля';
                header('Location: /admin/password/request');
                return false;
            }

            if ($_POST['passwordConfirm'] !== $_POST['password']) {
                $_SESSION['error'] = 'Введенные пароли не совпадают';
                header('Location: /admin/password/request');
                return false;
            }

            $user = User::find($_SESSION['user']);
            $user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $user->passChanged = 1;
            $user->save();
            header('Location: /');
        } else {
            header('Location: /');
        }
    }

    public function passwordForm()
    {
        $userCheck = User::find($_SESSION['user']);
        if ($userCheck && $userCheck->role === 1) {

            $this->view->render('newPassword.html.twig', ['error' => $_SESSION['error']]);
            if ($_SESSION['error'] !== null) {
                unset($_SESSION['error']);
            }
        } else {
            header('Location: /');
        }
    }

    public function formAddUser()
    {
        $userCheck = User::find($_SESSION['user']);
        if ($userCheck && $userCheck->role === 1) {
            if (array_key_exists('error', $_SESSION)) {
                $this->view->render('admin.add.html.twig', ['error' => $_SESSION['error']]);
                unset($_SESSION['error']);
            } else {
                $this->view->render('admin.add.html.twig');
            }
        } else {
            header('Location: /');
        }
    }

    public function addUser()
    {
        $userCheck = User::find($_SESSION['user']);
        if ($userCheck && $userCheck->role === 1) {
            if (empty($_POST['login'])) {
                $_SESSION['error'] = 'Заполните поле логина';
                header('Location: /admin/user/add/request');
                return false;
            }

            if (empty($_POST['password'])) {
                $_SESSION['error'] = 'Заполните поле пароля';
                header('Location: /admin/user/add/request');
                return false;
            }

            if (empty($_POST['passwordConfirm'])) {
                $_SESSION['error'] = 'Заполните поле проверки пароля';
                header('Location: /admin/user/add/request');
                return false;
            }

            $user = User::where('login', $_POST['login'])->first();

            if ($user) {
                $_SESSION['error'] = 'Пользователь с таким логином уже существует';
                header('Location: /admin/user/add/request');
                return false;
            }

            if ($_POST['passwordConfirm'] !== $_POST['password']) {
                $_SESSION['error'] = 'Введенные пароли не совпадают';
                header('Location: /admin/user/add/request');
                return false;
            }

            $newUser = new User();
            $newUser->login = strip_tags($_POST['login']);
            $newUser->password = password_hash(strip_tags($_POST['password']), PASSWORD_BCRYPT);
            $newUser->lastname = $_POST['lastName'];
            $newUser->firstname = $_POST['firstName'];
            $newUser->midname = $_POST['midName'];
            $newUser->birthdate = $_POST['birthDate'];
            $newUser->save();
            header('Location: /');
        } else {
            header('Location: /');
        }
    }

    public function viewUser($id)
    {
        $userCheck = User::find($_SESSION['user']);
        if ($userCheck && $userCheck->role === 1) {
            $user = User::find($id);
            $avatar = Photo::where('user_id', $id)->where('current', 1)->first();
            $this->view->render('user.html.twig', ['user' => $user, 'avatar' => $avatar]);
        } else {
            header('Location: /');
        }
    }
}