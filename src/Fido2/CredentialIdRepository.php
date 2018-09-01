<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace U2FAuthentication\Fido2;

interface CredentialIdRepository
{
    public function hasCredentialId(string $credentialId): bool;
    public function getCredentialId(string $credentialId): AttestedCredentialData;
}