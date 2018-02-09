# OCI2PDO
A complete wrapper for OCI8 that looks, feels, and smells like standard PDO.

## Why?
It was an experament to see if we could use OCI instead of PDO to connect to our Oracle DB without refactoring 15 years worth of code.. It works, but it's slower than PDO, so we ended up sticking with PDO.

## How?
Include all 3 classes in the same directory and include the `oPDO.php` script. The API is just like PDO with a few very minor exceptions which are documented in the code. Skim the comments in the code before using. 

I searched high and low before writing this, so I'm posting it to hopefully save someone else some time.
