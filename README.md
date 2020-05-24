# Requests handler
## Description
This docker-compose service can handle requests via PHP embedded web server with NGINX-based load balancer, store it to the PostgreSQL database and show requests list on the main page
## Deploy instruction:
1. `docker-compose build` - **build services**
1. `docker-compose up` - **run services**
1. `docker-compose up --scale app={amount} -d` - *if you want to create more instances of app, use this command* 
### After this you can reach application on http://localhost:8000/

#### This repository was build during study [at this course](https://learndocker.online/courses/3/overview/content "Writing Code")