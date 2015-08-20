/**
 * Documentation: http://docs.azk.io/Azkfile.js
 */
// Adds the systems that shape your system
systems({
  frontend: {
    // Dependent systems
    depends: ['db'],
    // More images:  http://images.azk.io
    image: { docker: "azukiapp/php-fpm:5.6" },
    // Steps to execute before running instances
    provision: [
      // prepare storage folder because persisted
      "mkdir -p ./app/storage/attachments ./app/storage/cache ./app/storage/logs ./app/storage/meta ./app/storage/sessions ./app/storage/views",
      "find ./app/storage -type d -print0 | xargs -0 chmod 0755",
      // "find ./app/storage -type f -print0 | xargs -0 chmod 0644",
      // install system dependencies (composer, npm and bower)
      "composer install",
      "npm install",
      "node_modules/.bin/bower --allow-root install",
      // compile assets and start livereload
      "node_modules/.bin/gulp",
      // create db and run migrations
      'mysql -P $MYSQL_PORT -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD -e "CREATE DATABASE $MYSQL_DATABASE"',
      "php artisan migrate",
    ],
    workdir: "/azk/#{manifest.dir}/#{system.name}",
    shell: "/bin/bash",
    wait: 20,
    mounts: {
      '/azk/#{manifest.dir}/#{system.name}'                        : sync("./#{system.name}"),
      '/azk/#{manifest.dir}/#{system.name}/vendor'                 : persistent("#{system.name}/vendor"),
      '/azk/#{manifest.dir}/#{system.name}/app/storage'            : persistent("#{system.name}/app/storage"),
      '/azk/#{manifest.dir}/#{system.name}/app/js/bower_components': persistent("#{system.name}/app/js/bower_components"),
      '/azk/#{manifest.dir}/#{system.name}/node_modules'           : persistent("#{system.name}/node_modules"),
      '/azk/#{manifest.dir}/#{system.name}/composer.lock'          : path("#{system.name}/composer.lock"),
      '/azk/#{manifest.dir}/#{system.name}/bootstrap/compiled.php' : path("#{system.name}/bootstrap/compiled.php"),
      // '/azk/#{manifest.dir}/#{system.name}/.env.php': path("#{system.name}/.env.php"),
    },
    scalable: {"default": 1},
    http: {
      domains: [ "paperwork.#{azk.default_domain}" ]
    },
    ports: {
      // exports global variables
      http: "80/tcp",
    },
    envs: {
      // Make sure that the PORT value is the same as the one
      // in ports/http below, and that it's also the same
      // if you're setting it in a .env file
      APP_DIR: "/azk/#{manifest.dir}/#{system.name}",
      COMPOSER_ENV: "development",
    },
  },
  db: {
    depends: [],
    image: {"docker": "azukiapp/mysql:5.6"},
    shell: "/bin/bash",
    wait: 25,
    mounts: {
      "/var/lib/mysql": persistent("#{manifest.dir}/mysql"),
    },
    ports: {
      data: "3306:3306/tcp",
    },
    envs: {
      MYSQL_ROOT_PASSWORD: "secret",
      MYSQL_USER: "paperwork",
      MYSQL_PASS: "paperwork",
      MYSQL_DATABASE: "paperwork",
    },
    export_envs: {
      DATABASE_URL  : "mysql2://#{envs.MYSQL_USER}:#{envs.MYSQL_PASS}@#{net.host}:#{net.port.data}/${envs.MYSQL_DATABASE}",
      MYSQL_HOST    : "#{net.host}",
      MYSQL_PORT    : "#{net.port.data}",
      MYSQL_USER    : "#{envs.MYSQL_USER}",
      MYSQL_PASSWORD: "#{envs.MYSQL_PASS}",
      MYSQL_DATABASE: "#{envs.MYSQL_DATABASE}",
    },
  },
});
