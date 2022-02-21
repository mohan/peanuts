# Peanuts

Discussion board for teams.


License: GPL (Free as in free peanuts.)

Work in progress: true


* PHP application.
* No external database system needed. Uses built-in CSVDB.
* *Only one team password*. No user passwords to remember.
* Host it yourself on your LAN. (Ex: `php -S 127.0.0.1:8080`).


## Note
* **Not tested**, do not use.
* Please feel free to implement it yourself.
* Internet hosting is discouraged.
	* Built and designed to be used as an internal application in our unit.
	* Less secure is enough, as it is hosted on LAN - Local Area Network.



## Peanuts Specification

* Discussion board for teams on LAN for collaboration/notes/knowledge-base/project-management/talk.
* User, post and comment only, for simplicity.
* Password protected. Team password only. No user password.
* Team members write posts.
* Team members comment on posts.
* Nested comments are supported.
* Markdown support.
* Shortcore support. Example: [calendar year=yyyy month=mm mark=16].
* Likes/stars/emoji support.
* Banner post.
* Sticky posts.
* Login page banner text.
* Notification panel for new comments for current user posts.
* Multi-team support, with a single installation.
	* Primary team can be used for posts between all teams.
	* Individual teams post in their team board.
* Posts contain title and body fields only.
	* Posts listing page contains title, user, last-updated-at, likes/stars/emoji count, comments count.
	* Sorting by title, created-at and updated-at, latest commented-on.
	* Create new post page with title and body fields.
	* QuickPost in listing page. 128 characters maximum; insert into title; wit bottom String.length display.
	* Title field may contain hashtags. Will be used for tags. Display latest 32 bottom of posts. Tags page contains tags listing.
	* Counts are stored in post meta JSON.
	* Guest post - guest name is stored in post meta.
	* Post total view count - store in post meta.
* Comments support markdown.
* Likes/stars/emoji is stored as a comment.
* Own markdown implementation with checklist support displayed as checkboxes.
* No external libraries.
* No support for search.
* No support for uploads/images. Instead post a link to an image.



## Thoughts

1. "Textbox is precious. Use it wisely." - Peanuts team member
2. "Markdown is what you are looking for. Markup is for me." - Peanuts team member
3. "We are cross domain." - Peanuts team member
4. "Elephants walk slow, take time, slow baking." - Peanuts team member
5. "Patience is a virtue." - Unknown
6. "We cook. We eat. We share recipe." - Peanuts team member
7. "0 1 2 3 5 8 13 21 34 55 89 144 233 377." - Unknown
8. "Marmalade." - Peanuts team member



## Recipe

### SweetPeanutMild++ v0.0.2

Ingredients:
* ~128 raw peanuts
* 1.5  spoons vegetable oil
* 1.5  spoons sugar
* 1.5  spoons flour
* ~16  spoons water

Process:
1. Roast peanuts in vegetable oil.
2. Mix water, sugar and flour.
3. Add syrup to roasted peanuts and stir until caramel is coated well.
4. Enjoy SweetPeanutMild++ with your team.



## References

* SquirrelMail
* WP
* Rails
* Django
* Flask
* ExpressJS
* jQuery
* Bootstrap
* Git
