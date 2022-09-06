# Lead-project

## installatie
### Lokaal met Lando
De Lando file kan je vinden in de root van het project.

Verander naam van de **.env.example** file naar **.env**

In de **.env** file en verander **MEILISEARCH_HOST** door het volgende:

```
MEILISEARCH_HOST=http://127.0.0.1:7700
```

Uncomment ook de database properties in de **.env** en verander de values hiervan naar jouw huidige database configuratie.  
Voor de Lando mysql database is dit de configuratie:
```
DB_CONNECTION=mysql
DB_HOST=database
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```



Voer de volgende commando's uit:
```
lando start
lando php artisan key:generate
lando ssh
./meilisearch &
exit
lando php artisan migrate
lando php artisan migrate:fresh --seed
lando php artisan jwt:secret
```



### Productie met Linux

Verander naam van de **.env.example** file naar **.env**

Voer dit commando uit:
```php artisan key:generate```

Uncomment de database properties in de **.env** en verander de values hiervan naar de productie database configuratie.  

JWT secret aanpassen:  
```php artisan jwt:secret ```

Ga naar de **.env** file en verander de value van **MEILISEARCH_HOST** naar het ip adres van de server met poort 7700.

Maak een script aan in de server met de volgende code:
```
#!/bin/sh

./meilisearch --http-addr 192.168.10.10:7700 #ip adres van de server
```

Maak script executable:

```
chmod u+x /path/to/script/start-meilisearch.sh
```


Ga naar de system folder:

```cd /etc/systemd/system```

Maak meilisearch service aan:

```touch start-meilisearch.service```

Voeg de volgende code toe aan de service:
```
[Unit]
Description= Start meilisearch voor laravel

[Service]
WorkingDirectory=/home/vagrant/code/lead-project #root folder van lead-project
ExecStart=/home/vagrant/scripts/start-meilisearch.sh #locatie van het script

[Install]
WantedBy=multi-user.target
```

Start service:

```sudo systemctl start start-meilisearch.service```

Enable service zodat hij opgestart wordt bij het booten:

``` sudo systemctl enable start-meilisearch.service```

Database opzetten en seeden:  
```
php artisan migrate
php artisan migrate:fresh --seed 
```

## Login accounts
Als de database geseed is kan je gebruik maken van de volgende accounts.  
  
Het main account:  
Email: test@test.be  
Wachtwoord: test

De andere accounts waarmee je kan inloggen zijn de accounts van de employees. In de applicatie kan je navigeren naar de overview pagina van een company ***/companies/{companyId}***. Hier heb je de mogelijkheid om een employee te bewerken. Bij het bewerken wordt de employee zijn/haar email ook weergegeven. Dit email kan je gebruiken met het wachtwoord ***test***