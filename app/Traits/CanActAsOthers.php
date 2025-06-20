<?php

namespace App\Traits;

use Illuminate\Contracts\Auth\Authenticatable;

trait CanActAsOthers
{
    public function canActAs(Authenticatable $user): bool
    {
        return $this->sudo && ! $user->sudo && $this->getAuthIdentifier() !== $user->getAuthIdentifier();
    }

    public function actAs(Authenticatable $user): void
    {
        throw_unless($this->getAuthIdentifier() === auth()->id(), 'Execute `actAs()` only on the current logged user');
        throw_unless($this instanceof $user, 'Both models have to be the same Authenticable class');
        throw_unless($this->canActAs($user), 'Can\'t run `actAs()` on a non-sudo user');

        throw_if($this->isActing(), 'You\'re already acting as an user');

        session(['currentUserSimulating' => [
            'id' => auth()->id(),
            'class' => $this::class,
            'remember' => auth()->viaRemember()
        ]]);

        auth()->login($user, remember: false);
    }

    public function isActing(): bool
    {
        throw_unless($this->getAuthIdentifier() === auth()->id(), 'Execute `isActing()` only on the current logged user');

        return session()->exists('currentUserSimulating')
            && session('currentUserSimulating.class') === $this::class;
    }

    public function stopActingAs()
    {
        throw_unless($this->isActing(), 'Can\'t stop acting, no acting was detected');
        throw_unless($this->id === auth()->id(), 'Execute `isActing()` only on the current logged user');

        auth()->logout();

        $currentUserSimulating = session()->remove('currentUserSimulating');
        $modelClassName = $currentUserSimulating['class'];

        throw_unless($modelClassName, 'Couldn\'t obtain the user simulating model class name');
        throw_unless($this instanceof $modelClassName, 'The model class name was tempered with');

        $admin = $modelClassName::where($this->getAuthIdentifierName(), $currentUserSimulating['id'])->first();

        if (is_null($admin)) return;

        if (! $admin->canActAs($this)) {
            logger()->alert("User {$admin->getAuthIdentifier()}, was able to act as another user!");
            return;
        }

        auth()->login(
            $admin,
            boolval($currentUserSimulating['remember'])
        );
    }
}
