# Flight Tracker

Flight Tracker is a real-time flight simulation application that allows users to track live flights across the globe. Users can easily monitor the status of flights in real-time, view detailed flight information, and observe the flight paths on an interactive world map.

The application provides a seamless user experience by displaying flight details such as flight numbers, departure and arrival locations, estimated times, and current status (e.g., airborne, landed, delayed). The map interface allows users to follow the live progress of each flight, making it perfect for aviation enthusiasts, travelers, and anyone curious about the status of flights worldwide.

---

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Application Configuration](#application-configuration)
---

## Requirements

1. **Docker Desktop**: Since this project is based on `docker-compose`, you will need Docker Desktop installed and running on your system. You can download and install Docker Desktop from the official Docker website: [Docker Desktop Download](https://www.docker.com/products/docker-desktop).

## Installation

To install and set up the project, follow these steps:

1. Clone the repository:

    ```bash
    git clone https://github.com/corcunda/flight-tracker.git
    ```

2. Copy the .env.example file inside project root folder and rename to .env

3. Inside the .env file place your OpenWeather apikey:

    ```bash
    OPENWEATHER_API_KEY=your_api_key
    ```
4. Inside the .env file place your necessary Pusher configuration:

    ```bash
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_HOST=
    PUSHER_PORT=443
    PUSHER_SCHEME=https
    PUSHER_APP_CLUSTER=
    ```
5. Install the necessary dependencies:

    ```bash
    npm install
    ```

---

## Usage

After setting up the project, you can run it locally using the following commands.

1. **Start the application**:  
Run the following command in your terminal to build and start the application using Docker Compose:
```
docker-compose up --build
```
2. **Install Passport**:  
You need this just for the first time. To install Laravel Passport, follow these steps:

**a)** Go to your docker desktop and find the container flight-app and open the terminal

**b)** Inside the container terminal run:
```
php artisan passport:install
```

**c)** When prompt, write yes.

**d)** Copy the ID and the SECRET and place in your .env file.
```
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=generated_passport_secret
```
**e)** After, clear the configuration and cache in the same terminal:
```
php artisan config:clear
php artisan cache:clear
```

3. **Access the application**:  
After your docker is running, navigate on your browser:
```
http://localhost:8484/
```

3. **Stop the application**:  
On the same terminal that is running the docker, you can press **CTRL-C** to stop it.

4. **Remove the application**:  
To remove the application from docker you can run on the terminal:
```
docker-compose down
```
---

## Application Configuration

You can configure the application by editing the `simulation.php` file located in the `config` folder.

In the `simulation.php` file, you can set:

- **Desired simulation routes**: Define the routes that will be simulated in the application.
- **Simulation settings**: Adjust settings related to the simulation, such as speed, time intervals, or other preferences.
- **Cities for weather data**: Specify the cities from which weather data will be retrieved to display relevant information about each flight.

Make sure to review and update the configuration file to meet your simulation needs.