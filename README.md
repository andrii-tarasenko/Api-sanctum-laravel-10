Основні вимоги

Model:
    User (Поля: name, email, password)
    Task (Поля: title, description, status (наприклад, pending, in progress, completed), user_id, team_id)
    Comment (Поля: id, content, task_id, user_id)
    Team (Поля: id, name)
    TeamUser - Проміжна таблиця для зв'язку користувачів з командами (team_id, user_id)

API endpoint:

   POST /register -  Реєстрація нового користувача.
   POST /login -  Авторизація користувача.
   POST /logout -  Вихід з системи (для аутентифікованих користувачів).

   GET /tasks - Отримання списку всіх задач (тільки для аутентифікованих користувачів, повертає задачі поточного користувача).
   POST /tasks - Створення нової задачі.
   GET /tasks/{id} -  Отримання інформації про конкретну задачу.
   PUT /tasks/{id} -  Оновлення інформації про задачу.
   DELETE /tasks/{id} -  Видалення задачі. 

   POST /tasks/{taskId}/comments -  Додавання коментаря до задачі.
   GET /tasks/{taskId}/comments -  Отримання всіх коментарів до задачі.
   DELETE /comments/{id}: Видалення коментаря. 

   POST /teams -  Створення нової команди.
   GET /teams -  Отримання списку команд користувача.
   POST /teams/{teamId}/users -  Додавання користувача до команди.
   DELETE /teams/{teamId}/users/{userId} -  Видалення користувача з команди. 
