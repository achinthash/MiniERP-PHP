# Installation

1. **Clone or download the repository.**

2. **Move the project folder to your web server's root directory** (e.g., `xampp/htdocs`).

3. **Import the provided SQL file** to set up the database.

4. **Copy `.env.example` to `.env`** and update the database credentials.

5. **Update the `.htaccess` file:**  
   Replace the `RewriteRule` path with your actual project directory, for example:  
   ```apache
   RewriteRule ^ /yourproject/public/login.php [QSA,L]
