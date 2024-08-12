# Personal Finance Tracker

## Project Summary

The Personal Finance Tracker is a web-based application designed to help users manage their income and expenses. It provides a simple interface for logging transactions, visualizing financial data, and now includes user authentication for personalized tracking.

## Key Features

- User registration and login
- Log income and expenses
- View recent transactions
- Visualize monthly financial summary with charts
- Secure personal data isolation

## Technology Stack

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Visualization**: Chart.js
- **Server**: WAMP (Windows, Apache, MySQL, PHP)

## File Structure

```
www/
└── finance_tracker/
    ├── index.php
    ├── login.php
    ├── register.php
    ├── logout.php
    ├── db_connect.php
```

## How It Works

1. Users register for an account or log in to an existing account.
2. Once logged in, users can input financial transactions through a web form.
3. PHP processes the input and stores data in a MySQL database, associated with the user's account.
4. Recent transactions are displayed in a table on the webpage, showing only the logged-in user's data.
5. JavaScript, using the Chart.js library, creates a bar chart showing monthly income and expenses for the user.
6. The application provides a dashboard with financial summaries and visualizations specific to each user.

## Setup

1. Install WAMP server
2. Create a MySQL database named `finance_tracker`
3. Run the SQL commands in `db_setup.sql` to set up the necessary tables
4. Place all project files in the `www/finance_tracker/` directory of your WAMP installation
5. Access the application via `http://localhost/finance_tracker/`

## Usage

1. Navigate to the application URL
2. Register for a new account or log in to an existing one
3. Use the form to add new income or expense transactions
4. View your recent transactions and monthly summary on the dashboard
5. Log out when finished to secure your data

## Security Features

- Passwords are hashed before storing in the database
- User sessions are used to maintain login state
- Each user can only access their own financial data

## Future Enhancements

- Detailed reporting and analytics
- Budget setting and tracking
- Data export functionality
- Category management for transactions
- Password reset functionality
- Two-factor authentication

## Notes for Developers

- CSS is included directly in each PHP file for simplicity. For larger projects, consider moving to external CSS files.
- Ensure to sanitize and validate all user inputs to prevent SQL injection and other security vulnerabilities.
- Regularly backup the database to prevent data loss.
