## Setup

- `git clone https://github.com/Abdulrahmanh95/alert_sys_pessl_test.git`.
- `composer install`.
- `composer dump-autoload`.
- Install redis if it's not.
- Run redis server locally on `127.0.0.1:6379` as it's being used by `bin/queue-worker`.
- Create a mysql database called `pessl_test` with username `root` and without password.
- Run the migrations inside `database/migrations` to set up database tables.
- Import `docs/Pessl Test_Abdulrahman_Hashem.postman_collection.json` in postman.
- Run `Store Stations Payload` request and pass the payloads file to it.

**NOTE**: The criteria used not to disturb the user with too many alerts is by checking 
if there is an alert sent with the same type two hours ago at most.