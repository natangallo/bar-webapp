# Usa l'immagine base di PHP con Apache
FROM php:8.2-apache

# Installa le estensioni PHP necessarie
RUN docker-php-ext-install pdo pdo_mysql

# Copia i file dell'applicazione nella cartella /var/www/html del contenitore
COPY ./app /var/www/html

# Copia i file di configurazione personalizzati, se presenti
# COPY ./config/my.cnf /etc/mysql/my.cnf

# Espone la porta 80
EXPOSE 80
