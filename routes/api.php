<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// These are the routes for our Person API
//
// We will use route model binding to let Laravel do most of the heavy lifting
// so, we need to import the correctly namespaced Person model class
use App\Person;

// This route will return an array of all Person objects in our people table.
// Laravel automatically knows to send a JSON formatted response to a route that
// returns either an object or an array.
Route::get('/people', function () {
  // We can use the all() method on our Person model class, which was inherited from the
  // Eloquent/Model class from the Laravel framework. It will query the database for us and
  // return an array of Person objects. If there are no records in the table, it will return
  // an empty array. We can directly return the result:
  // return Person::all();

  // The all() method is fine if we know that there will only be a small result set to return.
  // However, in most production applications you will want to be able to retrieve results in
  // smaller chunks (e.g. 15 rows of data at a time). This is called pagination and Laravel
  // has a built-in function to do this for us that adds the necessary meta data. Try this
  // to see the difference:
  return Person::paginate(10);
});

// For routes that result in loading a model, we can take advantage of Laravel's
// route-model-binding to automatically validate the dynamic "id" parameter from the URL
// e.g. https://week12.dev/api/people/66  <- 66 is the id value
// In this case we are using the placeholder {person} - the name must match the model name -
// and then Laravel will attempt to load a Person model from the database with the given "id" value.
// If it finds a match it will populate the $person variable. If not, it will return a 404 error.
// This means that we do not have write any bloilerplate code to attempt to fetch the record and
// test to see if we got a result, and if not handle the error. It is all done for us.
Route::get('/people/{person}', function (Person $person) {
  return $person;
});

// This route will accept form fields and allow us to create a new Person object and store it in
// the database.  We call the create method (inherited from Eloquent) on the Person class. It takes
// an associative array of property names and values as an argument.
Route::post('/people', function (Request $request) {
  // The returned object will also include the automatically created properties for the
  // id and timestamp fields.
  return Person::create([
    // The property names must match our database field names and must be white listed for
    // mass updating using the $fillable property in our model class (i.e. Person)
    // We can get the form input values using the input method on the $request object.
    'first_name' => $request->input('first_name'),
    'last_name' => $request->input('last_name'),
    'age' => $request->input('age')
  ]);
});

// For our update routes, we can use either the patch or the put HTTP verbs so we will use the
// match method on the Route class facade which takes an array of HTTP verbs as the first argument.
// We will also take advantage of Laravel's route-model-binding to automatically validate the
// dynamic "id" parameter from the URL - here we are using the placeholder {person} - and then
// Laravel will attempt to load a Person model from the database with the given "id" value.
// If it finds a match it will populate the $person variable. If not, it will return a 404 error.
Route::match(['patch', 'put'], '/people/{person}', function(Request $request, Person $person) {

  // Now we can simply update the respective property values with the form data as we did for
  // the create route.  Of course we only want to change the property values if we received new
  // values.  If the form field is not present we should ignore it.
  if (!empty($request->input('first_name'))) {
    $person->first_name = $request->input('first_name');
  }
  if (!empty($request->input('last_name'))) {
    $person->last_name = $request->input('last_name');
  }
  if (!empty($request->input('age'))) {
    $person->age = $request->input('age');
  }

  // when we have set all of the changed values, we can now save it out to the database.
  $person->save();

  // Return to the client a properly formatted JSON response including the details of the
  // updated Person object.
  return response()->json([
    "status" => 200,
    "message" => "OK",
    "data" => $person
    ]);

});

// To illustrate the value of route-model-binding used in the update route, we will manually
// parse the "id", attempt to load the model and handle the exception if not found.
// You can use either method.
Route::delete('/people/{id}', function (int $id) {
  // We will use the Eloquent findOrFail() method to retrieve the Person object by id value.
  // If no record exists with that id it will automatically return a 404 error response.
  // The findOrFail() method works almost exactly the same as the standard find() method, but
  // this saves us from having to wrap the main logic of our function in an if ($person) {} block
  // and manually setting the failure response object.
  $person = Person::findOrFail($id);

  // We will make a copy of the Person object before we delete it. We will use this in the response
  // data sent back to the client.
  $deletedPerson = $person;

  // Now call the delete() method on our Person object.
  $person->delete();

  // Finally, we can return a properly formatted JSON response including the details of the
  // deleted object.
  return response()->json([
    "status" => 200,
    "message" => "OK",
    "data" => $deletedPerson
  ]);
});
