<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\User;

use Twig\Environment;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;

use Utils\Auth;
use Utils\Session;

class FrontController
{
    private Environment $twig;

    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $errors = Session::get('errors');
        Session::forget('errors');
        $message = Session::get('message');
        Session::forget('message');

        $twig->addGlobal('message', $message);
        $twig->addGlobal('errors', $errors);
        $twig->addGlobal('authenticated', Auth::isAuthenticated());

        $this->twig = $twig;
    }

    /**
     * @return Response
     */
    public function main(ServerRequest $request): Response
    {
        $queryParams = $request->getQueryParams();

        $page = 1;

        if (isset($queryParams['page'])) {
            $page = (int) $queryParams['page'];
        }

        $queries = [];

        if (isset($queryParams['name'])) {
            $queries['name'] = $queryParams['name'];
        }

        if (isset($queryParams['email'])) {
            $queries['email'] = $queryParams['email'];
        }

        if (isset($queryParams['status'])) {
            $queries['status'] = $queryParams['status'];
        }

        $tasks = Task::query()
            ->when(empty($queries), function ($query) {
                $query->latest('id');
            })
            ->when($queryParams['status'] ?? null, function ($query, $order) {
                $query->orderBy('status', $order);
            })
            ->when($queryParams['name'] ?? null, function ($query, $order) {
                $query->orderBy('name', $order);
            })
            ->when($queryParams['email'] ?? null, function ($query, $order) {
                $query->orderBy('email', $order);
            })
            ->simplePaginate(3, ['*'], 'page', $page);

        $total = Task::query()->count();

        return response($this->twig->render('pages/index.twig', [
            'tasks' => $tasks->items(),
            'pagesCount' => ceil($total / $tasks->perPage()),
            'queries' => http_build_query($queries),
        ]));
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return response($this->twig->render('pages/tasks/create.twig'));
    }

    /**
     * @return Response
     */
    public function store(ServerRequest $request): Response
    {
        $validation = validate($request->getParsedBody(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'task' => 'required',
        ]);

        if ($validation->passes()) {
            $data = $request->getParsedBody();

            Task::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'text' => $data['task'],
                'status' => Task::IN_PROCESS,
            ]);

            Session::put('message', 'Успешно добавлено!');

            return redirect('/');
        }

        Session::put('errors', $validation->errors()->all());

        return redirect('/tasks/create');
    }

    /**
     * @return Response
     */
    public function edit($request, $variables): Response
    {
        if (isset($variables['id'])) {
            $task = Task::query()->where('id', $variables['id'])->first();

            if ($task) {
                return response($this->twig->render('pages/tasks/edit.twig', [
                    'task' => $task,
                ]));
            }
        }

        return redirect('/');
    }

    /**
     * @return Response
     */
    public function update(ServerRequest $request, $variables): Response
    {
        $validation = validate($request->getParsedBody(), [
            'task' => 'required',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->passes()) {
            if (isset($variables['id'])) {
                $data = $request->getParsedBody();

                $task = Task::query()->where('id', $variables['id'])->first();

                if ($task) {
                    $credentials = [
                        'text' => $data['task'],
                        'status' => $data['status'],
                    ];

                    if ($task->text != $data['task']) {
                        $credentials['is_modified'] = true;
                    }

                    $task->update($credentials);

                    return redirect('/');
                }

                $validation->errors()->add('id','exists', 'This task not exists');
            }
        }

        Session::put('errors', $validation->errors()->all());

        return redirect('/tasks/' . $variables['id'] . '/edit');
    }

    /**
     * @return Response
     */
    public function login(): Response
    {
        return response($this->twig->render('pages/auth/login.twig'));
    }

    /**
     * @return Response
     */
    public function authenticate(ServerRequest $request): Response
    {
        $validation = validate($request->getParsedBody(), [
            'login' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        if ($validation->passes()) {
            $data = $request->getParsedBody();

            $user = User::query()->where('login', $data['login'])->first();

            if ($user) {
                if (password_verify($data['password'], $user->password)) {
                    Session::put('user', $user->id);

                    return redirect('/');
                }
            }

            $validation->errors()->add('login', 'exists', 'This credentials not exists');
        }

        Session::put('errors', $validation->errors()->all());

        return redirect('/login');
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        if (Session::has('user')) {
            Session::forget('user');
            Session::forget('authenticated');
        }

        return redirect('/');
    }
}