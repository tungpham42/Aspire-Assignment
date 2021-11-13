Aspire Assignment for Tung Pham
=

Introduction
-

My name is Tung Pham and I create this app as a mini-aspire API. This is a Loan app built with Laravel 8. There are 2 main fields in the `loans` table. The first one is `amount` which is the amount of loan in VND. The second one is `term` which is the duration of loan in week(s). The loan term can vary from 1 week to 52 weeks.
 
How to install Laravel 8
-

Firstly, you must check if your server meets the requirements:

    PHP >= 7.3

    BCMath PHP Extension

    Ctype PHP Extension

    Fileinfo PHP Extension

    JSON PHP Extension

    Mbstring PHP Extension

    OpenSSL PHP Extension

    PDO PHP Extension

    Tokenizer PHP Extension

    XML PHP Extension

There are many ways to install Laravel, but in this test I will use the easiest

    composer create-project laravel/laravel loan-app
    
    cd loan-app
    
If you don't have Composer installed, just go to https://getcomposer.org/download/ to get it.

Setup Database
-

Navigate to .env file, then edit

    DB_DATABASE=sample_db
    
    DB_USERNAME=sample_user
    
    DB_PASSWORD=sample_pass
    
with your desired credentials

Migration
-

Run this code to create Model, Migration and Factory files

    php artisan make:model Loan -mf
    
We add the following code into the Loan model file inside the app/Models folder

    protected $fillable = [
    
        'amount',
        
        'term',
        
    ];
    
Now we modify the migrate file in the /database/migrations folder

    public function up()
    
    {
    
        Schema::create('loans', function (Blueprint $table) {
        
            $table->id();
            
            $table->integer('amount');
            
            $table->integer('term');
            
            $table->timestamps();
            
        });
        
    }
<img width="649" alt="Screen Shot 2021-11-13 at 11 05 36" src="https://user-images.githubusercontent.com/3462233/141605042-91433360-e007-4813-8746-f32963e1a41f.png">

Then we run the following command to create the table

    php artisan migrate

Seeding
-

We should modify the Factory file first, this file is located in /database/factories folder

    public function definition()
    
    {
    
        return [
        
            'amount' => $this->faker->numberBetween(500, 500000000),
            
            'term' => $this->faker->numberBetween(1, 52),
            
        ];
        
    }

<img width="664" alt="Screen Shot 2021-11-13 at 12 36 47" src="https://user-images.githubusercontent.com/3462233/141607214-3c3dc5f2-58f1-4970-b9af-63752164760c.png">

After that, we modify the DatabaseSeeder.php file inside the /database/seeders folder

    public function run()
    
    {
    
        Loan::factory(500)->create();
        
    }

<img width="394" alt="Screen Shot 2021-11-13 at 10 50 15" src="https://user-images.githubusercontent.com/3462233/141604669-37e28d58-af22-4096-8550-dea8b1005681.png">

Then we run the following command

    php artisan migrate:fresh --seed
 
Creating the API
-

Firstly, run the command

    php artisan make:controller LoanController --resource

Now, go to app/Http/Controllers/LoanController, there are 6 methods that we should take care of: index, store, show, update, destroy, and repay.

1. Index

    public function index()
    
    {

        return Loan::orderBy('created_at', 'asc')->get();  //returns values in ascending order
    
    }
    
<img width="515" alt="index" src="https://user-images.githubusercontent.com/3462233/141601058-5fcea9fd-8b5f-4967-99c9-4214487f73fe.png">
    
2. Store

    public function store(Request $request)

    {

        $this->validate($request, [ //inputs are not empty or null

            'amount' => 'required',

            'term' => 'required',

        ]);

        $loan = new Loan;

        $loan->amount = $request->input('amount'); //retrieving user inputs

        $loan->term = $request->input('term');  //retrieving user inputs

        $loan->save(); //storing values as an object

        return $loan; //returns the stored value if the operation was successful.

    }
    
<img width="515" alt="store" src="https://user-images.githubusercontent.com/3462233/141601068-51df9b62-4cc2-4c9d-a77a-205fc4d1b2c3.png">
    
3. Show

    public function show($id)

    {

        return Loan::findorFail($id); //searches for the object in the database using its id and returns it.

    }
    
<img width="334" alt="show" src="https://user-images.githubusercontent.com/3462233/141601066-266b3ad6-db23-4f17-a421-2bfdb9561ab1.png">
    
4. Update

    public function update(Request $request, $id)

    {

        $this->validate($request, [ //inputs are not empty or null

            'amount' => 'required',

            'term' => 'required',

        ]);

        $loan = Loan::findorFail($id); // uses the id to search values that need to be updated.

        $loan->amount = $request->input('amount'); //retrieving user inputs

        $loan->term = $request->input('term');  //retrieving user inputs

        $loan->save(); //storing values as an object

        return $loan; //returns the stored value if the operation was successful.

    }
    
<img width="466" alt="update" src="https://user-images.githubusercontent.com/3462233/141601063-268401e8-1a6f-4409-84d8-48fdbf4bd557.png">
    
5. Destroy

    public function destroy($id)

    {

        $loan = Loan::findorFail($id); //searching for object in database using ID

        if($loan->delete()){ //deletes the object

            return 'Deleted successfully'; //shows a message when the delete operation was successful.

        }

    }
    
<img width="369" alt="destroy" src="https://user-images.githubusercontent.com/3462233/141601061-8c151c5f-7a36-4390-8136-f53132b20769.png">
    
6. Repay

    public function repay($id)
    
    {
    
        $loan = Loan::findorFail($id);
        
        return number_format($loan->amount / $loan->term, 2) . ' VND';
        
    }
 
<img width="633" alt="repay" src="https://user-images.githubusercontent.com/3462233/141601059-3dce9828-785d-421b-aea1-400de5438aec.png">
Registering and Listing routes
-

Put the following line into the api.php file, which is inside the /routes folder

    Route::resource('loans', LoanController::class);
    
    Route::get('/loans/{loan}/repay', [
    
        "uses" => 'App\Http\Controllers\LoanController@repay',
        
        "as" => 'loans.repay'
        
    ]);
    
Now we can list the routes with this command

    php artisan route:list

<img width="961" alt="Screen Shot 2021-11-13 at 08 35 59" src="https://user-images.githubusercontent.com/3462233/141600931-a01f3206-1a75-481a-a9e2-f83cf506e9ad.png">

Testing
-

I have used the Postman collection to test this app's API. I put it in the root folder.

Run
-

After all, we can now run this app in localhost

    php artisan serve
    
It will use port 8000, so please navigate to http://localhost:8000

Basic Authentication
-

As this app only allows authenticated users to use, we need to implement a Authentication system. For this test, I choose to use Basic Auth. I added `middleware('auth.basic')` to routes of api.php inside /routes folder. By default, the auth.basic middleware will assume the email column on your users database table is the user's "username". In this test, the username is `tung.42@gmail.com` and the password is `12345`.

<img width="732" alt="Screen Shot 2021-11-13 at 13 11 33" src="https://user-images.githubusercontent.com/3462233/141608183-b7ad2fb7-887d-450b-8a6f-101a8f0172db.png">

When you use the API, you must fill in the credentials.

<img width="370" alt="Screen Shot 2021-11-13 at 13 13 47" src="https://user-images.githubusercontent.com/3462233/141608221-de141a90-8691-4333-aa4c-f8a190195739.png">

If you have any query, please contact me at tung.42@gmail.com
