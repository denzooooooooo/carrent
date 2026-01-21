<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service Duffel Customer Users API v2
 * 
 * Gère les utilisateurs clients Duffel pour la facturation et l'historique
 * https://duffel.com/docs/api/customers
 */
class DuffelCustomerService
{
    protected $baseUrl = 'https://api.duffel.com/v2';
    protected $accessToken;
    protected $timeout;

    public function __construct()
    {
        $this->accessToken = config('services.duffel.key');
        $this->timeout = config('services.duffel.timeout', 30);
    }

    /**
     * Faire une requête HTTP à l'API Duffel Customer
     */
    protected function request(string $method, string $endpoint, array $data = []): ?array
    {
        if (empty($this->accessToken)) {
            Log::warning('Duffel Customer API: No access token configured');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Duffel-Version' => 'v2',
            ])->timeout($this->timeout)->$method($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            $errorBody = $response->body();
            $errorMessage = 'Unknown error';
            
            try {
                $errorJson = json_decode($errorBody, true);
                $errorMessage = $errorJson['message'] ?? $errorJson['error'] ?? $errorBody;
            } catch (\Exception $e) {
                $errorMessage = $errorBody;
            }

            Log::error('Duffel Customer API Error:', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'error' => $errorMessage,
            ]);

            return [
                'error' => true,
                'status' => $response->status(),
                'message' => $errorMessage,
            ];
        } catch (Exception $e) {
            Log::error('Duffel Customer API Exception:', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Créer un utilisateur client
     * 
     * @param array $data Données de l'utilisateur
     * @return array|null
     */
    public function createCustomerUser(array $data): ?array
    {
        Log::info('Creating Duffel Customer User:', [
            'email' => $data['email'] ?? null,
        ]);

        $payload = [
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ];

        // Champs optionnels
        if (!empty($data['phone'])) {
            $payload['phone'] = $data['phone'];
        }
        if (!empty($data['born_on'])) {
            $payload['born_on'] = $data['born_on'];
        }
        if (!empty($data['address'])) {
            $payload['address'] = $data['address'];
        }

        $result = $this->request('POST', '/users', $payload);

        if (!$result || isset($result['error'])) {
            Log::error('Failed to create Duffel customer user', [
                'error' => $result['message'] ?? 'Unknown error',
            ]);
            return null;
        }

        Log::info('Duffel Customer User created:', [
            'id' => $result['id'] ?? null,
        ]);

        return [
            'id' => $result['id'] ?? null,
            'email' => $result['email'] ?? $data['email'],
            'first_name' => $result['first_name'] ?? $data['first_name'],
            'last_name' => $result['last_name'] ?? $data['last_name'],
            'created_at' => $result['created_at'] ?? null,
        ];
    }

    /**
     * Récupérer un utilisateur client
     * 
     * @param string $userId ID de l'utilisateur Duffel (icu_xxx)
     * @return array|null
     */
    public function getCustomerUser(string $userId): ?array
    {
        $result = $this->request('GET', '/users/' . $userId);

        if (!$result || isset($result['error'])) {
            return null;
        }

        return [
            'id' => $result['id'] ?? null,
            'email' => $result['email'] ?? null,
            'first_name' => $result['first_name'] ?? null,
            'last_name' => $result['last_name'] ?? null,
            'phone' => $result['phone'] ?? null,
            'born_on' => $result['born_on'] ?? null,
            'created_at' => $result['created_at'] ?? null,
            'updated_at' => $result['updated_at'] ?? null,
        ];
    }

    /**
     * Lister les utilisateurs clients avec filtres
     * 
     * @param array $filters Filtres可选
     * @return array
     */
    public function listCustomerUsers(array $filters = []): array
    {
        $params = [];
        
        if (!empty($filters['email'])) {
            $params['email'] = $filters['email'];
        }
        if (!empty($filters['first_name'])) {
            $params['first_name'] = $filters['first_name'];
        }
        if (!empty($filters['last_name'])) {
            $params['last_name'] = $filters['last_name'];
        }

        $endpoint = '/users';
        if (!empty($params)) {
            $endpoint .= '?' . http_build_query($params);
        }

        $result = $this->request('GET', $endpoint);

        if (!$result || isset($result['error'])) {
            return [
                'users' => [],
                'total' => 0,
            ];
        }

        $users = collect($result['data'] ?? $result)->map(function ($user) {
            return [
                'id' => $user['id'] ?? null,
                'email' => $user['email'] ?? null,
                'first_name' => $user['first_name'] ?? null,
                'last_name' => $user['last_name'] ?? null,
                'created_at' => $user['created_at'] ?? null,
            ];
        })->toArray();

        return [
            'users' => $users,
            'total' => count($users),
        ];
    }

    /**
     * Mettre à jour un utilisateur client
     * 
     * @param string $userId ID de l'utilisateur
     * @param array $data Nouvelles données
     * @return array|null
     */
    public function updateCustomerUser(string $userId, array $data): ?array
    {
        Log::info('Updating Duffel Customer User:', [
            'user_id' => $userId,
        ]);

        $payload = [];
        
        if (array_key_exists('first_name', $data)) {
            $payload['first_name'] = $data['first_name'];
        }
        if (array_key_exists('last_name', $data)) {
            $payload['last_name'] = $data['last_name'];
        }
        if (array_key_exists('phone', $data)) {
            $payload['phone'] = $data['phone'];
        }
        if (array_key_exists('address', $data)) {
            $payload['address'] = $data['address'];
        }

        $result = $this->request('PATCH', '/users/' . $userId, $payload);

        if (!$result || isset($result['error'])) {
            Log::error('Failed to update Duffel customer user', [
                'user_id' => $userId,
                'error' => $result['message'] ?? 'Unknown error',
            ]);
            return null;
        }

        return $this->getCustomerUser($userId);
    }

    /**
     * Supprimer un utilisateur client
     * 
     * @param string $userId ID de l'utilisateur
     * @return bool
     */
    public function deleteCustomerUser(string $userId): bool
    {
        Log::info('Deleting Duffel Customer User:', [
            'user_id' => $userId,
        ]);

        $result = $this->request('DELETE', '/users/' . $userId);

        return !$result || !isset($result['error']);
    }

    /**
     * Créer un groupe d'utilisateurs
     * 
     * @param string $name Nom du groupe
     * @return array|null
     */
    public function createCustomerUserGroup(string $name): ?array
    {
        $result = $this->request('POST', '/user_groups', [
            'name' => $name,
        ]);

        if (!$result || isset($result['error'])) {
            return null;
        }

        return [
            'id' => $result['id'] ?? null,
            'name' => $result['name'] ?? $name,
            'created_at' => $result['created_at'] ?? null,
        ];
    }

    /**
     * Assigner un utilisateur à un groupe
     * 
     * @param string $userId ID de l'utilisateur
     * @param string $groupId ID du groupe
     * @return bool
     */
    public function assignUserToGroup(string $userId, string $groupId): bool
    {
        $result = $this->request('POST', '/user_groups/' . $groupId . '/users', [
            'user_id' => $userId,
        ]);

        return !$result || !isset($result['error']);
    }

    /**
     * Récupérer les commandes d'un utilisateur
     * 
     * @param string $userId ID de l'utilisateur
     * @param array $filters Filtres optionnels
     * @return array
     */
    public function getUserOrders(string $userId, array $filters = []): array
    {
        $params = ['user_id' => $userId];
        
        if (!empty($filters['status'])) {
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['created_at'])) {
            $params['created_at'] = $filters['created_at'];
        }

        $endpoint = '/air/orders?' . http_build_query($params);
        
        // Note: L'endpoint réel peut varier selon l'API Duffel
        // Ceci est une implémentation conceptuelle
        return [
            'orders' => [],
            'total' => 0,
        ];
    }

    /**
     * Synchroniser un utilisateur local avec Duffel
     * 
     * @param \App\Models\User $user
     * @return array|null
     */
    public function syncUser($user): ?array
    {
        // Vérifier si l'utilisateur a déjà un ID Duffel
        if ($user->duffel_customer_id) {
            return $this->getCustomerUser($user->duffel_customer_id);
        }

        // Créer un nouvel utilisateur Duffel
        return $this->createCustomerUser([
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone ?? null,
            'born_on' => $user->born_on?->format('Y-m-d') ?? null,
        ]);
    }
}

