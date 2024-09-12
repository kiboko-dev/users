<?php

namespace App\Http\Services;

use App\Constants\AuthConstants;
use App\Enums\ActionEnum;
use App\Http\Repositories\UserRepository;
use App\Models\User;
use App\Traits\LogTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    use LogTrait;

    protected const REFRESH_TOKEN_SESSION_KEY = 'session_%s';

    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function login(string $phone, ?string $code = null): array
    {
        if (null === $code) {
            return [];
        }

        if (substr($phone, -4) !== $code) {
            throw new UnauthorizedException('Bad Code');
        }
        $user = User::wherePhone($phone)->firstOrFail();

        return $this->respondWithToken($user);
    }

    public function registration($phone, $lastName, $name, $middleName, $email): User
    {
        return DB::transaction(function () use ($phone, $lastName, $name, $middleName, $email) {
            $user = $this->userRepository->create(
                lastName: $lastName,
                name: $name,
                middleName: $middleName,
                phone: $phone,
                email: $email
            );
            self::logChanges(
                action: ActionEnum::CREATE,
                after: $user
            );

            return $user;
        }, 3);
    }

    public function logout(): void
    {
        auth()->logout();
    }

    protected function respondWithToken(User $user): array
    {
        $refreshToken = $this->generateRefreshToken($user->id);

        $accessToken = auth(AuthConstants::API_GUARD)
            ->setTTL(AuthConstants::TOKEN_TTL)
            ->tokenById($user->id);

        session([
            $this->getRefreshTokenSessionKey($refreshToken) => [
                'refresh_token' => $refreshToken,
                'user_id' => $user->id,
            ],
        ]);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => Carbon::parse($this->generateExpired())->toDateTimeString(),
            //            'user' => $user,
        ];
    }

    private function generateRefreshToken(string $userId): string
    {
        return hash('sha256', uniqid(microtime().$userId.Str::random(), true));
    }

    private function getRefreshTokenSessionKey(string $refreshToken): string
    {
        return sprintf(self::REFRESH_TOKEN_SESSION_KEY, $refreshToken);
    }

    private function generateExpired(): int
    {
        return Carbon::now()->addSeconds(AuthConstants::TOKEN_TTL)->timestamp;
    }
}
