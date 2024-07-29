# Webapp-skeleton

This application is a desktop like one page web application which connect to backend via REST API and render data with javascript only.
My webapp skeleton uses the [Slim4](https://github.com/odan/slim4-skeleton) framework for REST API backend to handle bussiness logic with database access and [W2UI](https://github.com/vitmalina/w2ui) frontend framework to render JSON responses. Both framework can be found on Github also.
The backend code is checked with phplint, sniffer, code style checker, static analizer and fully covered with unittests.

## Main features
- add new tables and update existing ones through REST API
- authenticate user with credentials
- user capabilities:
    - store profile data with preferences and avatar photo
	- add new user by the addministrator only
- password cababilities:
    - first login via link which sent in email
    - use rules for new password
	- last 5 repetition is forbidden
	- expire password periodically
	- block the login after using 3 wrong password

## REST API

### Requirements
- PHP 7.4
- [SLIM4 0.20.1](https://github.com/odan/slim4-skeleton/releases/tag/0.20.1)

### Installation
- see the SLIM4 installation on the above link
- edit the user profile data of the administrator in source code file named '/src/Domain/DBUpdate/Files/Update0004.php'
- create the database named 'webapp_skeleton_dev'
- call the {your url}/public/api/dbupdate URL to install tables and their records

## Frontend

### Requirements
- [W2UI 2.0.0.latest](https://github.com/vitmalina/w2ui)

### Start the application
- start the application with the {your url}/public link

## File structure
- app: frontend application
- bin: console commands
- config: slim4 config files
- logs: slim4 logfiles
- public: public folder for browsers
- resources: resource files
- src: backend source files
- templates: template files
- tests: unit test files
- tools: build and deploy commands for frontend application


