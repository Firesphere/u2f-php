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

class PublicKeyCredentialDescriptor implements \JsonSerializable
{
    public const PUBLIC_KEY_CREDENTIAL_TYPE_PUBLIC_KEY = 'public-key';

    public const AUTHENTICATOR_TRANSPORT_USB = 'usb';
    public const AUTHENTICATOR_TRANSPORT_NFC = 'nfc';
    public const AUTHENTICATOR_TRANSPORT_BLE = 'ble';
    public const AUTHENTICATOR_TRANSPORT_INTERNAL = 'internal';

    private $type;

    private $id;

    /**
     * @var string[]
     */
    private $transports;

    /**
     * @param string[] $transports
     */
    public function __construct(string $type, string $id, array $transports = [])
    {
        $this->type = $type;
        $this->id = $id;
        $this->transports = $transports;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getTransports(): array
    {
        return $this->transports;
    }

    public function jsonSerialize()
    {
        $json = [
            'type' => $this->type,
            'id' => base64_encode($this->id),
        ];
        if (!empty($this->transports)) {
            $json['transports'] = $this->transports;
        }

        return $json;
    }
}
