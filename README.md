# Framy

Framy is an annotation interface for giving frame semantics to texts. In particular, it is optimized to work with FrameNet.
You need a webserver with php and MySQL running on it.

Things to do to run Framy:
* Create a MySQL database.
* Load the `data/framy.sql` file in it.
* Open `loadFrameNet.php`, remove the `exit()` command, set `$addr` with the folder containing the frames
XML files and run the script. After that, add the `exit()` command again, just for security reasons.
* Open `loadTraining-simple.php`, remove the `exit()` command, set `$File` with the TXT file containing
the sentences and run the script. After that, again restore the `exit()` command.
* Go to `index.php` and enjoy.
