Aspire Assignment for Tung Pham
=

Introduction
-

My name is Tung Pham and I create this app as a mini-aspire API. This is a Loan app built with Laravel 8. There are 3 main fields in the `loans` table. The first one is `amount` which is the amount of loan in VND. The second one is `term` which is the duration of loan in week(s). The loan term can vary from 1 week to 52 weeks. The third one is `repayment` which is the weekly repayment.
 
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
            
            $table->double('repayment')->default('0');
            
            $table->timestamps();
            
        });
        
    }
<img width="591" alt="Screen Shot 2021-11-14 at 09 28 20" src="https://user-images.githubusercontent.com/3462233/141665121-ebb196e1-dccd-4b00-b045-ad827f191064.png">


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
        
        DB::table('users')->insert([
        
            'name' => "Tung Pham",
            
            'email' => 'tung.42@gmail.com',
            
            'password' => bcrypt('12345'),
            
        ]);
        
    }

<img width="454" alt="Screen Shot 2021-11-13 at 13 56 55" src="https://user-images.githubusercontent.com/3462233/141609338-b9a64b9c-0016-42ad-948e-c41c46f4dc2a.png">


Then we run the following command

    php artisan migrate:fresh --seed
 
<img width="517" alt="Screen Shot 2021-11-14 at 09 27 50" src="https://user-images.githubusercontent.com/3462233/141665138-225bc57b-7f47-4a22-858f-76ed6f33734f.png">
 

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
        
        $loan->repayment = $loan->amount / $loan->term;
        
        $loan->save();
        
        return number_format($loan->repayment, 2) . ' VND';
        
    }
 
<img width="572" alt="Screen Shot 2021-11-14 at 09 37 42" src="https://user-images.githubusercontent.com/3462233/141665284-46d96def-ddae-4871-bab7-b6c45afb2c27.png">


<img width="545" alt="Screen Shot 2021-11-14 at 09 29 02" src="https://user-images.githubusercontent.com/3462233/141665168-fafe3b66-928a-4217-9d74-254cc9269913.png">

Registering and Listing routes
-

Put the following line into the api.php file, which is inside the /routes folder

    Route::resource('loans', LoanController::class)->middleware('auth.basic');
    
    Route::get('/loans/{loan}/repay', [
    
        "uses" => 'App\Http\Controllers\LoanController@repay',
        
        "as" => 'loans.repay'
        
    ])->middleware('auth.basic');
    
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

Run the API
-

- http://localhost:8000/api/loans -> Get all loans
- http://localhost:8000/api/loans/42 -> Get loan with ID 42
- http://localhost:8000/api/loans -> Create new loan, with POST method and `amount` and `term` values
- http://localhost:8000/api/loans/42?amount=5000&term=52 -> Update loan, with PATCH method
- http://localhost:8000/api/loans/42 -> Delete loan, with DELETE method
- http://localhost:8000/api/loans/42/repay -> Repay weekly amount

If you have any query, please contact me at tung.42@gmail.com
