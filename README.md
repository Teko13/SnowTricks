## Description

Initiated by snowboard enthusiast Jimmy Sweat, this project aims to develop a collaborative website for snowboarding, focusing on popularizing the sport and facilitating learning of various snowboarding tricks.

## Purpose

The project's core purpose is to create a vibrant, user-generated platform to share experiences and knowledge about snowboarding, thereby attracting a broader audience and potential brand partnerships.

## Objectives

Key functionalities of the website include:

- Snowboard Tricks Directory: Starting with an initial list of 10 tricks, allowing community contributions.
- Trick Management: Enable creation, modification, and viewing of snowboard tricks.
- Discussion Forum: Each trick features a dedicated discussion space for community engagement.

## Website Pages

- Homepage: Lists snowboard tricks.
- Add New Trick: User interface for submitting new tricks.
- Edit Trick: Allows updating existing trick details.
- Trick Details: Provides trick information and discussion forum.

## Technical Specifications

- SEO-Friendly URLs: Intuitive and search engine optimized.
- Adaptive Design: Compliant with provided wireframes, suitable for all devices.
- Data Initialization: External bundle for initial trick data, with self-reliant development approach

## Installation

1. Clone the GitHub Repository: run

   ```
   git clone https://github.com/Teko13/SnowTricks.git
   ```

2. Install Dependencies: run

   ```
   composer install
   ```

3. Configure environment variable: Rename the example.env file to .env and configure your database URL and mail server by uncommenting the corresponding lines and entering your information.

4. Generate the database using the script in sql_database_generation/snowtricks.sql directly in your database management system.

5. Load Data: run

   ```
   php bin/console d:f:l
   ```

6. Start server: go to public/ folder and start your local server

## Admin

- Username: admin
- Password: admin
