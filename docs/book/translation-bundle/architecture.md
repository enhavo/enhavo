## Architecture

One of the main idea of the translation module, is that the underlaying
model, don\'t need to know if it should be translated or not. That will
automatically have effects to the database structure, as the model
don\'t need to save the translation data into his table. Following
graphic explain how the translation data can be edited in the default
form workflow. The translation data will just add and remove to the
form, so if you get the normal data out of the form, it\'s untouched by
the translation data and therefore it can you used as before. Meanwhile
the translation data is stored in cache and will only apply to the
database if the underlaying model is also stored to the database.

![image](/images/translation_architecture.png)
