
# Tw1tt3r - A  Twitter Clone  built with Symfony

Tw1tt3r is a Twitter clone built as a school project for YDAYS, where students work only on Wednesdays for half a week. It is built on the  Symfony framework  and has features such as creating articles,  personal profiles, user permission, and an admin dashboard.

## Setup

To use Tw1tt3r, you need to have the following installed:

-   PHP 7.3 or higher
-   Composer
-   Symfony 5.3 or higher
-   MySQL  or  PostgreSQL

To install Tw1tt3r, follow these steps:

1.  Clone the repository from GitHub:  `git clone https://github.com/Glama107/tw1tt3r.git`
2.  Install the dependencies:  `cd tw1tt3r && composer install`
3.  Create a database and configure the  `.env`  file with your  database credentials
4.  Run the database migrations:  `php bin/console doctrine:migrations:migrate`
5.  Run the  Symfony server:  `symfony server:start`

You should now be able to access Tw1tt3r at  `http://localhost:8000`.

## Features

### Creating Articles

Tw1tt3r allows users to create articles, which can be seen by other users. Users can create, edit, and delete their own articles. The articles are displayed on the user's profile page and on the home page.

### Personal Profile

Each user has a  personal profile page  that displays their articles, their username, and their profile picture. Users can edit their profile picture and their username.

### User Permission

Tw1tt3r has different levels of user permission. Regular users can create, edit, and delete their own articles. Admin users have additional permissions, such as editing and deleting any article, and managing user accounts.

### Admin Dashboard

The admin dashboard is only accessible to users with admin permission. It allows admins to manage user accounts, such as creating new users, editing user details, and deleting users. Admins can also view all articles and edit or delete any article.

## Conclusion

Tw1tt3r is a basic Twitter clone built with Symfony. It has features such as creating articles, personal profiles,  user permission, and an admin dashboard. It is a great project for students who want to learn Symfony and build a  web application.
