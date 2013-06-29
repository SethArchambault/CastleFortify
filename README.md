CastleFortify
=============

Save and Share your Castle Doctrine designs. Also, designs are saved in simple JSON code, so they can be used in any of your own apps. 

http://castlefortify.com

Here's a map I often use:
http://castlefortify.com/c/1eb1be1

Thanks for checking Castle Fortify out, and let me know if you have any ideas on how to make it better!

# Install

1. Edit config.php with proper database connection info
2. Import sql into database (/sql/castle_fortify.sql)
3. Run composer.phar install ([Download Composer Here](http://getcomposer.org))

# Roadmap for Future Versions

[View Roadmap on Trello](https://trello.com/board/castle-fortify/5161a8347040e6623a009092)

# Join the Discussion

[Official Castle Doctrine Forum Post](http://thecastledoctrine.net/forums/viewtopic.php?id=33&p=1)

Thanks for all your feedback!

# You Can Contribute!

**The Quick Way**

>   Good for typos, quick fixes 

1. Switch to the "Develop" branch
2. Click the file that needs the edit
3. Click "Edit"
4. Make your change, Update the commit summary, click Commit changes!

>   Thanks so much! 

[Or Try The Pro Way](https://github.com/SethArchambault/CastleFortify/wiki/Contributing)

# Codebase

While I've tried to keep things as simple as possible, I decided to try out require.js and a Publish / Subscribe event firing model to keep the functionality from getting too confusing.  Whether or not I succeeded at the goal I guess I'll find out in 6 months when I come back and try to change something :p

One unfortunate side effect of this latest change is that if you are making changes to the javascript code, you should uncomment this line in select.php:

  <script data-main="/app.js" src="/lib/require/require.js"></script>
  
and comment out the other script line immediately above it.

When the code goes to production, you'll have to install r.js and run

    r.js -o build.js

To create a minified version of app.js and then recomment and uncomment the lines again.

Not ideal.. But it does allow us to create clean, seperate javascript modules without losing any performance. Plus the code doesn't make my eyes bleed now.

So that's a plus.

# Versioning

major.minor.patch
