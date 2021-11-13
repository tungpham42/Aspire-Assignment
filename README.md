Aspire Assignment for Tung Pham
=

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
    
We add the following code into the Loan model file

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

Then we run the following command to create the table

    php artisan migrate

Seeding
-

We should modify the Factory file first

    public function definition()
    
    {
    
        return [
        
            'amount' => $this->faker->numberBetween(500, 500000000),
            
            'term' => $this->faker->numberBetween(1, 52),
            
        ];
        
    }

Then, we run the following command

    php artisan make:seeder LoanSeeder

After that, we modify the Seeder file inside the /database/seeders folder

    public function run() {
    
        Loan::factory()->times(42)->create();
        
    }
    
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
    
3. Show

    public function show($id)
    
    {
    
        return Loan::findorFail($id); //searches for the object in the database using its id and returns it.
        
    }
    
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
    
5. Destroy

    public function destroy($id)
    
    {
    
        $loan = Loan::findorFail($id); //searching for object in database using ID
        
        if($loan->delete()){ //deletes the object
        
            return 'Deleted successfully'; //shows a message when the delete operation was successful.
            
        }
        
    }
    
6. Repay
