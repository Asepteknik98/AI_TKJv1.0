# AI Assistant TKJ - SMK Jaya Buana

Simple PHP native project to help teachers and students of TKJ.

Setup:

1. Put project in `C:/xampp/htdocs/AI_TKJ`.
2. Create database and run `database/schema.sql` in MySQL.
3. Edit `config/config.php` to set DB credentials and `openai_api_key`.
4. Run `php database/seed_admin.php` to create initial admin user.
5. Open `http://localhost/AI_TKJ` in browser.

Notes:
- Uses OpenAI Chat Completions (set your API key in `config/config.php`).
- Simple OOP for Database and AI helper. Expand as needed.
