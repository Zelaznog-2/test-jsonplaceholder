<?php

namespace App\Console\Commands;

use App\Http\Controllers\UsersController;
use App\Jobs\CommentsPerUserJob;
use App\Models\User;
use Illuminate\Console\Command;
use App\Traits\ServiceJsonPlaceholderTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class FetchUserData extends Command
{

    use ServiceJsonPlaceholderTrait;

    /**
     * The user controller instance.
     *
     * @var mixed
     */
    protected $userCtrl = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-user-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch user data from the API and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting the user data fetch process...');

        // Fetch data from the API
        $users = $this->getDataUser();

        if (empty($users)) {
            $this->error('No user data found or an error occurred.');
            return;
        }

        $this->userCtrl = new UsersController();

        // Process and store the user data
        foreach ($users as $user) {
            try {
                $this->info("Storing user: {$user['name']}");

                // Check if the user already exists
                $existingUser = User::where('email', strtolower($user['email']))->first();

                // Prepare the request data
                $requestData = [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'address' => $user['address'] ? json_encode([
                        'street' => $user['address']['street'] ?? null,
                        'suite' => $user['address']['suite'] ?? null,
                        'city' => $user['address']['city'] ?? null,
                        'zipcode' => $user['address']['zipcode'] ?? null,
                        'geo' => [
                            'lat' => $user['address']['geo']['lat'] ?? null,
                            'lng' => $user['address']['geo']['lng'] ?? null,
                        ],
                    ]) : null,
                    'phone' => $user['phone'] ?? null,
                    'website' => $user['website'] ?? null,
                    'company' => $user['company'] ? json_encode([
                        'name' => $user['company']['name'] ?? null,
                        'catchPhrase' => $user['company']['catchPhrase'] ?? null,
                        'bs' => $user['company']['bs'] ?? null,
                    ]) : null,
                    'email' => strtolower($user['email']),
                    'password' => 'password', // Use a default password or generate one
                    'password_confirmation' => 'password', // Ensure password confirmation matches
                ];
                $request = request()->merge($requestData);


                if ($existingUser){
                    $response = $this->userCtrl->update($request, $existingUser->id);
                } else {
                    $response = $this->userCtrl->store($request);
                }

                if ($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
                    $type = 'created successfully.';
                    if ($existingUser) {
                        $type = 'update successfully.';
                    }
                    $this->info("User {$user['name']} {$type}");
                } else {
                    $this->error("Failed to store user {$user['name']}: " . $response->getContent());
                    Log::error("Failed to store user {$user['name']}: " . $response->getContent());
                }

            } catch (\Throwable $th) {
                $this->error("Failed to store/update user {$user['name']}: " . $th->getMessage());
                Log::error("Failed to store/update user {$user['name']}: " . $th->getMessage());
            }
        }

        $this->info('User data fetch process completed.');
        Log::info('User data fetch process completed.');
    }

    /**
     * Get Data User from the API.
     *
     * @return array
     */
    private function getDataUser(): array
    {
        try {
            $this->info('Fetching user data from the API...');
            $endpoint = '/users';
            $response = Cache::remember('users_data_api', now()->addMinutes(30) , function () use ($endpoint) {

                $apiResponse = $this->fetchDataFromApi($endpoint);
                if (isset($apiResponse['error'])) {
                    throw new \Exception("API Error: " . $apiResponse['message']);
                }
                
                
                return $apiResponse['data'];
            });

            $this->info('Data fetched successfully.');
            return $response;
        } catch (\Throwable $th) {
            $this->error('An error occurred while fetching data: ' . $th->getMessage());
            return [];
        }
    }
}
