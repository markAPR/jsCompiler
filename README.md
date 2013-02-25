jsCompiler
==========

A php based javascript minifier that can be run locally in MAMP /WAMP 

This project is a php based JavaScript minifier, based on Google's closure Compiler.

To use this tool, you will need to run the project in MAMP / WAMP

Directions for USE ::

Set the 'SCRIPT_PATH' constant to be the directory where you would like your packed file saved

Run compiler.php in your localhost environment

Choose the file that you would like to minify

Choose the level of compression you would like to use, either WHITESPACE_ONLY, SIMPLE_OPTIMISATIONS, or ADVANCED_OPTIMISATIONS,
( choose from the dropdown). See this url for an explanation of what you might get:

https://developers.google.com/closure/compiler/docs/api-ref

Choose whether the output is pretty printed or not

Hit  'Minify Script Now'

The tool will tell you where the file has been saved, and the packed size. 

If you receive an error message, you may need to check the syntax of your javascript.


This tool is provided free of charge, without warranty, or any implied contract, use it at your own risk.


