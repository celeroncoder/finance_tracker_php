# Personal Finance Tracker

## Project Summary

The Personal Finance Tracker is a web-based application designed to help users manage their income and expenses. It provides a simple interface for logging transactions and visualizing financial data.

## Key Features

- Log income and expenses
- View recent transactions
- Visualize monthly financial summary

## Technology Stack

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Visualization**: Chart.js
- **Server**: WAMP (Windows, Apache, MySQL, PHP)

## How It Works

1. Users input financial transactions through a web form.
2. PHP processes the input and stores data in a MySQL database.
3. Recent transactions are displayed in a table on the webpage.
4. JavaScript, using the Chart.js library, creates a bar chart showing monthly income and expenses.
5. The application provides a dashboard with financial summaries and visualizations.

## Setup

1. Install WAMP server
2. Create a MySQL database named `finance_tracker`
3. Place project files in the `www/finance_tracker/` directory of your WAMP installation
4. Access the application via `http://localhost/finance_tracker/`

## Future Enhancements

- User authentication
- Detailed reporting and analytics
- Budget setting and tracking
- Data export functionality
- Category management
