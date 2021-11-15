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

Navigate to `.env` file, then edit

    DB_DATABASE=sample_db
    
    DB_USERNAME=sample_user
    
    DB_PASSWORD=sample_pass
    
with your desired credentials

Migration
-

Run this command to create Model, Migration and Factory files

    php artisan make:model Loan -mf
    
We add the following code into the `Loan.php` model file inside the `app/Models` folder

    protected $fillable = [
    
        'amount',
        
        'term',
        
    ];
 
<img width="534" alt="Screen Shot 2021-11-14 at 14 42 59" src="https://user-images.githubusercontent.com/3462233/141672231-b6752e89-ba69-4008-8414-117e315cbc92.png">


Now we modify the migrate file `2021_11_12_125415_create_loans_table.php` (the file name may vary depending on the date you run the above command) in the `/database/migrations` folder. The `amount` and `term` will be integer, and `repayment` will be double with the default value `0`.

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


Then we run the following command to create the tables

    php artisan migrate

Seeding
-

We should modify the `LoanFactory.php` file first, this file is located in `/database/factories` folder. The `amount` will be generated randomly from `50000` to `500000000` VND and the `term` will be from `1` to `52` week(s).

    public function definition()
    
    {
    
        return [
        
            'amount' => $this->faker->numberBetween(50000, 500000000),
            
            'term' => $this->faker->numberBetween(1, 52),
            
        ];
        
    }

<img width="623" alt="Screen Shot 2021-11-15 at 14 28 55" src="https://user-images.githubusercontent.com/3462233/141739760-72cc7562-f4db-4417-b76d-beae12501602.png">


After that, we modify the `DatabaseSeeder.php` file inside the `/database/seeders` folder. When triggered, the seeder will create `500` loans into the `loans` table, and will create a sample user whom email is `tung.42@gmail.com` and password is `12345`. Also, I added the `admin` user to use the List all loans function.

    public function run()
    
    {
    
        Loan::factory(500)->create();
        
        DB::table('users')->insert([[
        
            'name' => "admin",
            
            'email' => 'admin@example.com',
            
            'password' => bcrypt('password'),
            
        ],
        
        [
        
            'name' => "Tung Pham",
            
            'email' => 'tung.42@gmail.com',
            
            'password' => bcrypt('12345'),
            
        ]]);
        
    }

<img width="443" alt="Screen Shot 2021-11-15 at 15 45 39" src="https://user-images.githubusercontent.com/3462233/141750274-2fd4ccd5-94d9-4e40-ba42-6bb3a7999321.png">



Then we run the following command

    php artisan migrate:fresh --seed
 
<img width="517" alt="Screen Shot 2021-11-14 at 09 27 50" src="https://user-images.githubusercontent.com/3462233/141665138-225bc57b-7f47-4a22-858f-76ed6f33734f.png">
 

Creating the API
-

Firstly, run the command

    php artisan make:controller LoanController --resource

Now, go to `app/Http/Controllers/LoanController.php`, there are 6 methods that we should take care of: `index`, `store`, `show`, `update`, `destroy`, and `repay`. The `repay` method must be written manually.

1. Index - get all loans, which is only accessible by admin, the credentials are `admin@example.com` and `password`

    public function index()
    
    {
    
        if (Auth::user()->name == 'admin') {
        
            return Loan::orderBy('created_at', 'asc')->get();  //returns values in ascending order
            
        } else {
        
            return 'Admin only';
            
        }
        
    }

<img width="574" alt="Screen Shot 2021-11-15 at 15 48 33" src="https://user-images.githubusercontent.com/3462233/141750705-78d2df38-4eb5-4c0a-a18a-b2e3304cd67a.png">

    
2. Store - create new loan, which is only accessible by admin, the credentials are `admin@example.com` and `password`. The values are validated. `amount` and `term` are required and must be number. `amount` must be greater than 50000 and `term` must be from 1 to 52.

    public function store(Request $request)

    {
        
        if (Auth::user()->name == 'admin') {
        
            $this->validate($request, [ //inputs are not empty or null

                'amount' => 'required|integer|gt:50000',

                'term' => 'required|integer|gte:1|lte:52',

            ]);

            $loan = new Loan;

            $loan->amount = $request->input('amount'); //retrieving user inputs

            $loan->term = $request->input('term');  //retrieving user inputs

            $loan->save(); //storing values as an object

            return $loan; //returns the stored value if the operation was successful.

        } else {
        
            return 'Admin only';
            
        }

    }
    
<img width="517" alt="Screen Shot 2021-11-15 at 16 09 04" src="https://user-images.githubusercontent.com/3462233/141753892-f0db6e21-8039-4a39-b111-bf6b55a4cf72.png">

    
3. Show - get a specific loan

    public function show($id)

    {

        return Loan::findorFail($id); //searches for the object in the database using its id and returns it.

    }
    
<img width="334" alt="show" src="https://user-images.githubusercontent.com/3462233/141601066-266b3ad6-db23-4f17-a421-2bfdb9561ab1.png">
    
4. Update - edit a specific loan, which is only accessible by admin, the credentials are `admin@example.com` and `password`. The values are validated. `amount` and `term` are required and must be number. `amount` must be greater than 50000 and `term` must be from 1 to 52.

    public function update(Request $request, $id)

    {
    
        if (Auth::user()->name == 'admin') {
        
            $this->validate($request, [ //inputs are not empty or null

                'amount' => 'required|integer|gt:50000',

                'term' => 'required|integer|gte:1|lte:52',

            ]);

            $loan = Loan::findorFail($id); // uses the id to search values that need to be updated.

            $loan->amount = $request->input('amount'); //retrieving user inputs

            $loan->term = $request->input('term');  //retrieving user inputs

            $loan->save(); //storing values as an object

            return $loan; //returns the stored value if the operation was successful.
            
        } else {
        
            return 'Admin only';
            
        }
            
    }
    
<img width="516" alt="Screen Shot 2021-11-15 at 16 08 52" src="https://user-images.githubusercontent.com/3462233/141753852-bb7694f6-9a9a-491f-8528-813bca45bc5c.png">


    
5. Destroy - delete a loan, which is only accessible by admin, the credentials are `admin@example.com` and `password`

    public function destroy($id)

    {

        if (Auth::user()->name == 'admin') {

            $loan = Loan::findorFail($id); //searching for object in database using ID

            if($loan->delete()){ //deletes the object

                return 'Deleted successfully'; //shows a message when the delete operation was successful.

            }
            
        } else {
        
            return 'Admin only';
            
        }

    }
    
<img width="358" alt="Screen Shot 2021-11-15 at 16 08 41" src="https://user-images.githubusercontent.com/3462233/141753806-38f48d68-545f-458a-8278-be872e2597d1.png">
    
6. Repay - save a repayment by dividing `amount` by `term`

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

Put the following line into the `api.php` file, which is inside the `/routes` folder

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

To create a test file, run the following command

    php artisan make:test LoanTest --unit
    
and
    
    php artisan make:test LoanSeederTest --unit
    
Then go to `tests/Unit/LoanTest.php` file. In this file, I have written 6 tests for 6 methods in the `LoanController.php` file. Plus, I created a method to Authenticate the test. Also, I have created a test file for seeding data `tests/Unit/LoanSeederTest.php`.

Here are the files

<img width="464" alt="Screen Shot 2021-11-15 at 13 12 51" src="https://user-images.githubusercontent.com/3462233/141731730-889031d6-bbb0-48d4-8362-1b70e0086fb9.png">

<img width="981" alt="Screen Shot 2021-11-15 at 16 28 11" src="https://user-images.githubusercontent.com/3462233/141756783-ad4028d8-c53f-45a1-8fdf-7fba7617bb5d.png">


To run the tests, run the command

    php artisan test
    
This is the result

<img width="443" alt="Screen Shot 2021-11-15 at 13 12 42" src="https://user-images.githubusercontent.com/3462233/141731762-6f514867-af12-4482-8b50-039308d45857.png">


All 7 tests have passed.

I have used the Postman collection to test this app's API. I put it in the root folder.

Run
-

After all, we can now run this app in localhost with this command

    php artisan serve
    
It will use port `8000`, so please navigate to http://localhost:8000

Basic Authentication
-

As this app only allows authenticated users to use, we need to implement a Authentication system. For this test, I choose to use Basic Auth. I added `middleware('auth.basic')` to routes of `api.php` inside `/routes` folder. By default, the auth.basic middleware will assume the email column on your users database table is the user's "username". In this test, the username is `tung.42@gmail.com` and the password is `12345`.

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

To get the full source code, you can use this command
    
    git clone https://github.com/tungpham42/Aspire-Assignment.git loan-app
    
    cd loan-app
    
    composer update
    
If you have any query, please contact me at tung.42@gmail.com
