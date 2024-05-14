<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating a Server with AWS and Docker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: linear-gradient(to bottom, #a24caf, #008CBA);    
            background-repeat: no-repeat;
            min-height: 100vh; 
            font-family: Arial, sans-serif;
            margin: 2rem;
            line-height: 1.5;
        }
        h1, h2, h3 {
            margin-bottom: 1rem;
        }
        p {
            margin-bottom: 1rem;
        }
        .step {
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .step h2 {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4 text-center">Creating a Server with AWS and Docker</h1>

        <div class="step">
            <h2>Step 1 — Create an Nginx Container</h2>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a directory for your project and navigate to it: <br><code>mkdir ~/docker-project</code> <br>and <br><code>cd ~/docker-project</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a <code>docker-compose.yml</code> file for launching the Nginx container: <code>nano docker-compose.yml</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Add the following configuration to the <code>docker-compose.yml</code> file.</p>
            <pre><code>
version: "3.9"
services:
    nginx:
      image: nginx:latest
      container_name: nginx-container
      ports:
       - 80:80
            </code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Launch the Nginx container: <code>docker-compose up -d</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Verify that the Nginx container is running: <code>sudo docker ps</code></p>
        </div>

        <div class="step">
            <h2>Step 2 — Create a PHP Container</h2>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a directory for your PHP code inside your project <code>mkdir ~/docker-project/php_code</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Clone your PHP code into the <code>php_code</code> directory. For example:</p>
            <pre><code>
git clone https://github.com/loris2659y2/progetto_AWS ~/docker-project/php_code/
            </code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a <code>Dockerfile</code> for the PHP container <code>nano ~/docker-project/php_code/Dockerfile</code> and add:</p>
            <pre><code>
FROM php:7.0-fpm
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli
            </code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a directory for Nginx inside your project directory: <code>mkdir ~/docker-project/nginx</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create an Nginx default configuration file to run your PHP application: <code>nano ~/docker-project/nginx/default.conf</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Add the following Nginx configuration to the <code>default.conf</code> file:</p>
            <pre><code>
server {  

    listen 80 default_server;  
    root /var/www/html;  
    index index.html index.php;  

    charset utf-8;  

    location / {  
    try_files $uri $uri/ /index.php?$query_string;  
    }  

    location = /favicon.ico { access_log off; log_not_found off; }  
    location = /robots.txt { access_log off; log_not_found off; }  

    access_log off;  
    error_log /var/log/nginx/error.log error;  

    sendfile off;  

    client_max_body_size 100m;  

    location ~ .php$ {  
    fastcgi_split_path_info ^(.+.php)(/.+)$;  
    fastcgi_pass php:9000;  
    fastcgi_index index.php;  
    include fastcgi_params;
    fastcgi_read_timeout 300;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;  
    fastcgi_intercept_errors off;  
    fastcgi_buffer_size 16k;  
    fastcgi_buffers 4 16k;  
    }  

    location ~ /.ht {  
    deny all;  
    }  
}
            </code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a Dockerfile inside the nginx directory to copy the <code>Nginx</code> default config file: <code>nano ~/docker-project/nginx/Dockerfile</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Add the following lines to the Dockerfile:</p>
            <pre><code>
FROM nginx
COPY ./default.conf /etc/nginx/conf.d/default.conf
            </code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Update the docker-compose.yml file with the following contents:</p>
            <pre><code>
version: "3.9"
services:
   nginx:
     build: ./nginx/
     ports:
       - 80:80
  
     volumes:
         - ./php_code/:/var/www/html/

   php:
     build: ./php_code/
     expose:
       - 9000
     volumes:
        - ./php_code/:/var/www/html/
            </code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Launch the containers: <code>cd ~/docker-project</code> and <code>docker-compose up -d</code></p>

        </div>

        <div class="step">
            <h2>Step 3 — Create a MariaDB Container</h2>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Edit the <code>docker-compose.yml</code> file to add an entry for a MariaDB container: <code>nano ~/docker-project/docker-compose.yml</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Update the <code>docker-compose.yml</code> file with the provided MariaDB configuration:</p>
            <pre><code>
version: "3.9"
services:
   nginx:
     build: ./nginx/
     ports:
       - 80:80
  
     volumes:
         - ./php_code/:/var/www/html/

   php:
     build: ./php_code/
     expose:
       - 9000
     volumes:
        - ./php_code/:/var/www/html/


   db:    
      image: mariadb  
      volumes: 
        -    mysql-data:/var/lib/mysql
      environment:  
       MYSQL_ROOT_PASSWORD: mariadb
       MYSQL_DATABASE: ecomdb 


volumes:
    mysql-data:
            </code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Run the following command: <code>docker-compose up -d</code></p> 
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a CLI session inside the MariaDB container: <code>sudo docker exec -it <container_name> /bin/bash</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Access MariaDB as the root user: <code>mariadb -u root -pmariadb</code></p>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Create a new user for the database: <code>mysql -u root -p</code></p>
            <pre><code>CREATE USER 'new_user'@'%' IDENTIFIED BY 'password';</code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Grant all privileges to the new user:</p>
            <pre><code>GRANT ALL PRIVILEGES ON *.* TO 'new_user'@'%';</code></pre>
        </div>

        <div class="step">
            <h2>Step 4 — Create a Database</h2>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Write the following line with the name of your database</p>
            <pre><code>CREATE DATABASE site;</code></pre>
            <p>Access it:</p>
            <pre><code>USE site;</code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>And populate it:</p>
            <pre><code>CREATE TABLE users (id INT NOT NULL PRIMARY KEY, email VARCHAR(100), password VARCHAR(100));</code></pre>
            <p><i class="fas fa-check-circle text-success mr-2"></i>Then you can add users by writing something like this:</p>
            <pre><code>INSERT INTO users (email, password) VALUES ('example@example.com', 'password123');</code></pre>
        </div>
    </div>
</body>
</html>
