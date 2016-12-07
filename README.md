# motif

Motif is a project designed to motivate users with social and charitable incentives (and allow me to practice working with PHP, SQL databases and APIs)

To recreate motif:

1. Start by registering an application with both Fitbit and Twitter.

  Fitbit: https://dev.fitbit.com/apps/new </br>
  Twitter: https://apps.twitter.com/app/new

2. Once you have registered a Fitbit application, you will be given a client id and a client secret. Place these into the <i>FitbitAuthorization.php</i> file, along with the redirect uri that the user should be redirected to after authentication.
  
3. Create a database and import <i>data.sql</i> to create the proper table structure. Update your database name and credentials in the files requiring databse access.

4. Install composer in the directory containing motif files.
    Directions on how to do this can be found here: <br/> https://getcomposer.org/doc/00-intro.md
    
5. Navigate to the directory containing the motif files in the terminal and call the following
    ```
        composer require league/oauth2-client
    ```
    This will create the files from the <i>composer</i> folder on your machine.
