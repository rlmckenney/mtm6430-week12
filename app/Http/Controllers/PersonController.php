<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Person::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Person::create([
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'age' => $request->input('age')
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        return $person;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        // We will make a copy of the Person object before we delete it.
      // We will use this in the response data sent back to the client.
      $deletedPerson = $person;

      // Now call the delete() method on our Person object.
      $person->delete();

      // Finally, we can return a properly formatted JSON response including
      // the details of the deleted object.
      return response()->json([
        "status" => 200,
        "message" => "OK",
        "data" => $deletedPerson
      ]);
    }
}
