
# Karmabot.app

Karmabot is a platform & discord bot to track, manage, and incentivize royalty payments for Solana communities & creators.

Royalties are unenforceable on-chain, making voluntary royalties the next best thing. Karmabot aims to provide creators with the tools they need to incentivize & increase voluntary royalty payments via gamification, rewards, and automated discord notifications.

[Karmabot.app](https://karmabot.app): Signup to install karmabot on your own server.

[Demo Video](https://www.youtube.com/watch?v=cddJuQeeBdM): Watch a video demo of Karmabot.

[Demo Discord](https://discord.gg/NxHjH2RF): Interact with karmabot on the demo server

[Demo Member Dashboard](https://karmabot.app/y00ts): Try out the demo member dashboard.


## Features


### Easily track royalty payments
  - Karmabot automatically tracks every transaction across all marketplaces to provide you with detailed analytics on your primary source of revenue.

![alt text](https://karmabot.app/dashboard.png)


### Reward your top supporters automatically
  - Karmabot will automatically distribute Karma Points to every royalty-paying wallet.
  - The rate at which Karma Points are distributed can be customized.

![alt text](https://karmabot.app/karma.png)

### Gamify paying royalties
  - Karmabot turns the chore of paying royalties into a competitive & rewarding game.
  - Members of your community can compete in the leaderboard, exchange their Karma Points for exclusive rewards and more.

![alt text](https://karmabot.app/member_dashboard.png)

### Set & forget Discord integration
  - Once installed on your discord server, Karmabot will frequently & automatically post notifications to engage your community.
  - Members of your community can also interact with Karmabot with the following commands:
    - /rewards: View available rewards
    - /leaderboard: View top 10 leaderboard
    - /karma {wallet}: Check the Karma Points balance of any wallet in the community
    - /airdrop (admin only): Airdrop Karma Points to any wallet

![alt text](https://karmabot.app/discordbot.png)

## Installation

**Requirements**


Install my-project with npm

```
PHP >= 8.1
Composer
MySQL
MySQL PHP Extension
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension
```

(All of these should be included in your PHP installation)

**Installation**

- Clone this repo
- Rename .env.example to .env and add the following after replacing the XXXX placeholders (You'll need to create a discord app, and an alchemy.com account)

```
DISCORD_URI=https://discord.com/api/
DISCORD_APP_ID=XXXX
DISCORD_PUBLIC_KEY=XXXX
DISCORD_BOT_TOKEN=XXXX
DISCORD_SECRET=XXXX

ALCHEMY_KEY=XXXX
ALCHEMY_URI=https://solana-mainnet.g.alchemy.com/v2/
```

- Create a new mysql database and update the following
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=XXXX
DB_USERNAME=XXXX
DB_PASSWORD=XXXX
```
- Run the following commands
```
  composer update
  php artisan key:generate
  php artisan migrate
  npm install
  ```
- Run ```crontab -e``` and input the following after replacing the placeholder path

```
 * * * * * cd ~/path/to/this/repo && php artisan schedule:run >> /dev/null 2>&1
```
    