# SocialNet Project
social networking web application built with PHP and MySQL.

## Setup Instructions

1.Database Configuration:
   - Import the provided `db.sql` file to create the database and `account` table:
     ```bash
     mysql -u root -p < db.sql
     ```
   - Ensure the credentials in `includes/db.php` match local MySQL settings.

2. File Permissions:
   - Ensure the web server has read permissions for the project folder:
     ```bash
     sudo chmod -R 755 /path/to/project
     ```

3. How to Access:
   - Navigate to `/admin/newuser.php` to create your first administrative user.
   - Access the main application at `/socialnet/signin.php`
# DoKhoaSocialNetwork
 
