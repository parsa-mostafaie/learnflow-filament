# Deeplearn

Deeplearn is a platform designed to help users learn anything efficiently and effectively using the Leitner box algorithm. Whether you're studying for exams, learning a new language, or acquiring new skills, Deeplearn offers a user-friendly and interactive experience to support your learning journey.

## Innovations

-   **Leitner System Integration:** Deeplearn utilizes the Leitner system to enhance memory retention and recall. This spaced repetition system helps users learn and retain information more effectively.
-   **Customizable Learning Paths:** Users can tailor their learning paths based on their goals and progress, ensuring a personalized learning experience.
-   **Interactive Exercises:** The platform features a variety of interactive exercises that adapt to the user's learning pace and style, making learning more engaging and effective.
-   **Daily Streaks and Progress Tracking:** Users can maintain their learning momentum with daily streaks and track their progress over time. This feature motivates users to stay consistent in their learning journey.
-   **Admin Panel for Management:** Deeplearn provides an admin panel for managing questions, courses, and users, making it easy for administrators to keep the platform updated and organized.
-   **And More...**

## Installation and Setup

### Prerequisites

-   PHP >= 8.x (Recommended 8.2.12)
-   Composer
-   Node.js and NPM
-   Git
-   PHP ext-zip in php.ini

### Steps to Install

1. Clone the project repository:
    
    If you have Cloned The repository previously, you can skip this step. but run commands below in the directory that conatins composer.json and README.md
    ```bash
    git clone https://github.com/parsa-mostafaie/deeplearn.git
    cd deeplearn
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install NPM dependencies:

    ```bash
    npm install
    ```

4. Create and configure the `.env` file:

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database and other environment settings.

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Link the storage directory:

    ```bash
    php artisan storage:link
    ```

7. Migrate the database and seed it:
    ```bash
    php artisan migrate --seed
    ```

8. Seed database from excel file:
    ```bash
    php artisan import:courses ./words.xlsx
    ```

9. Optimize For Better Performance:
    
    ```bash
    php artisan optimize:clear
    php artisan optimize
    ```

### Running the Application

Run the development server and Webpack build in one command:
`    composer run dev
   `

You can now access the application in your browser at `http://localhost:8000`.
You can login as super-admin By using ``pmostafaie1390@gmail.com`` as both of email & password

## Todos

- Complete Logging User activities and logs table
- Category for courses
- Purchasable Courses
- **And more...**


## Contact

If you need any help or have questions, you can reach out to us via email at `pmostafaie1390@gmail.com`.

---

Thank you for using Deeplearn! We hope you have a great learning experience.
