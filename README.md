# IckleMVC: PHP 5 based MVC Style framework / platform

## TODO
1. Create a parent/abstract model, S model class for all models to inherit from [started]
2. Make stricter MVC implementation. Move data-access from model into an object linked to the model
3. Flesh out scope concept
4. Actually do some development work on this project [started]

## Using the Core data access models

The IckleMVC\Models\Data_Model class is designed to populate and save from any non-joined SQL query.  It makes use of overloading (__call,__get,__set) to achieve this.  Access should be through a getProperty() or setProperty() call, as it is given that more complicated models and properties, override this with custom getters and setters, so they should be called via such.  As a result of this, there is functionality in place to convert a field name into a property name, and a property name into a field name.  Database fields should be all lower case, with words seperated with an underscore, properties should be in camelCase.   
