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

namespace U2FAuthentication\Tests\Unit\Fido;

use Base64Url\Base64Url;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use U2FAuthentication\Fido\KeyHandler;
use U2FAuthentication\Fido\PublicKey;
use U2FAuthentication\Fido\RegisteredKey;
use U2FAuthentication\Fido\SignatureRequest;
use U2FAuthentication\Fido\SignatureResponse;

/**
 * @group Unit
 */
final class SignatureResponseTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid response.
     */
    public function theSignatureRequestContainsAnError()
    {
        SignatureResponse::create([
            'errorCode' => 1,
        ]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid response.
     */
    public function theClientDataIsMissing()
    {
        SignatureResponse::create([
            'keyHandle' => 'Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ',
            'signatureData' => 'AQAAALowRQIgU-oyzSNitffUGZgRSEijbBytbz8ZwxZvnKSVC90oAm8CIQDoMW5ZtwUooptNB5M-2W_jSjT0yNOkWnU_w1e9aj7vMA',
        ]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid response.
     */
    public function theKeyHandleIsMissing()
    {
        SignatureResponse::create([
            'clientData' => 'eyJ0eXAiOiJuYXZpZ2F0b3IuaWQuZ2V0QXNzZXJ0aW9uIiwiY2hhbGxlbmdlIjoiRi16a3NSaDV0aHpLeVpSNk8wRnI3UXhsWi14RVg5X21OSDhIM2NIbl9QbyIsIm9yaWdpbiI6Imh0dHBzOi8vdHdvZmFjdG9yczo0MDQzIiwiY2lkX3B1YmtleSI6InVudXNlZCJ9',
            'signatureData' => 'AQAAALowRQIgU-oyzSNitffUGZgRSEijbBytbz8ZwxZvnKSVC90oAm8CIQDoMW5ZtwUooptNB5M-2W_jSjT0yNOkWnU_w1e9aj7vMA',
        ]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid response.
     */
    public function theSignatureDataIsMissing()
    {
        SignatureResponse::create([
            'keyHandle' => 'Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ',
            'clientData' => 'eyJ0eXAiOiJuYXZpZ2F0b3IuaWQuZ2V0QXNzZXJ0aW9uIiwiY2hhbGxlbmdlIjoiRi16a3NSaDV0aHpLeVpSNk8wRnI3UXhsWi14RVg5X21OSDhIM2NIbl9QbyIsIm9yaWdpbiI6Imh0dHBzOi8vdHdvZmFjdG9yczo0MDQzIiwiY2lkX3B1YmtleSI6InVudXNlZCJ9',
        ]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid response.
     */
    public function theTypeOfResponseIsInvalid()
    {
        SignatureResponse::create([
            'keyHandle' => 'Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ',
            'clientData' => 'eyJ0eXAiOiJiYWQudHlwZSIsImNoYWxsZW5nZSI6IkYtemtzUmg1dGh6S3laUjZPMEZyN1F4bFoteEVYOV9tTkg4SDNjSG5fUG8iLCJvcmlnaW4iOiJodHRwczovL3R3b2ZhY3RvcnM6NDA0MyIsImNpZF9wdWJrZXkiOiJ1bnVzZWQifQ',
            'signatureData' => 'AQAAALowRQIgU-oyzSNitffUGZgRSEijbBytbz8ZwxZvnKSVC90oAm8CIQDoMW5ZtwUooptNB5M-2W_jSjT0yNOkWnU_w1e9aj7vMA',
        ]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid response.
     */
    public function theUserPresenceByteIsInvalid()
    {
        SignatureResponse::create([
            'keyHandle' => 'Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ',
            'clientData' => 'eyJ0eXAiOiJuYXZpZ2F0b3IuaWQuZ2V0QXNzZXJ0aW9uIiwiY2hhbGxlbmdlIjoiRi16a3NSaDV0aHpLeVpSNk8wRnI3UXhsWi14RVg5X21OSDhIM2NIbl9QbyIsIm9yaWdpbiI6Imh0dHBzOi8vdHdvZmFjdG9yczo0MDQzIiwiY2lkX3B1YmtleSI6InVudXNlZCJ9',
            'signatureData' => '',
        ]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid response.
     */
    public function theCounterBytesAreInvalid()
    {
        SignatureResponse::create([
            'keyHandle' => 'Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ',
            'clientData' => 'eyJ0eXAiOiJuYXZpZ2F0b3IuaWQuZ2V0QXNzZXJ0aW9uIiwiY2hhbGxlbmdlIjoiRi16a3NSaDV0aHpLeVpSNk8wRnI3UXhsWi14RVg5X21OSDhIM2NIbl9QbyIsIm9yaWdpbiI6Imh0dHBzOi8vdHdvZmFjdG9yczo0MDQzIiwiY2lkX3B1YmtleSI6InVudXNlZCJ9',
            'signatureData' => 'AQAA',
        ]);
    }

    /**
     * @test
     */
    public function iCanCreateASignatureResponseAndUseIt()
    {
        $response = SignatureResponse::create(
            $this->getValidSignatureResponse()
        );

        static::assertEquals('{"typ":"navigator.id.getAssertion","challenge":"F-zksRh5thzKyZR6O0Fr7QxlZ-xEX9_mNH8H3cHn_Po","origin":"https://twofactors:4043","cid_pubkey":"unused"}', $response->getClientData()->getRawData());
        static::assertEquals(Base64Url::decode('MEUCIFPqMs0jYrX31BmYEUhIo2wcrW8_GcMWb5yklQvdKAJvAiEA6DFuWbcFKKKbTQeTPtlv40o09MjTpFp1P8NXvWo-7zA'), $response->getSignature());
        static::assertEquals('navigator.id.getAssertion', $response->getClientData()->getType());
        static::assertEquals('https://twofactors:4043', $response->getClientData()->getOrigin());
        static::assertEquals(Base64Url::decode('F-zksRh5thzKyZR6O0Fr7QxlZ-xEX9_mNH8H3cHn_Po'), $response->getClientData()->getChallenge());
        static::assertEquals('unused', $response->getClientData()->getChannelIdPublicKey());

        static::assertEquals(Base64Url::decode('Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ'), $response->getKeyHandle()->getValue());
        static::assertEquals(186, $response->getCounter());
        static::assertTrue($response->isUserPresence());

        $request = $this->prophesize(SignatureRequest::class);
        $request->getChallenge()->willReturn(Base64Url::decode('F-zksRh5thzKyZR6O0Fr7QxlZ-xEX9_mNH8H3cHn_Po'));
        $request->getApplicationId()->willReturn('https://twofactors:4043');
        $request->hasRegisteredKey(Argument::type(KeyHandler::class))->willReturn(true);
        $request->getRegisteredKey(Argument::type(KeyHandler::class))->willReturn(
            RegisteredKey::create(
                'U2F_V2',
                KeyHandler::create(Base64Url::decode('Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ')),
                PublicKey::create(Base64Url::decode('BFeWllSolex8diHswKHW6z7KmtrMypMnKNZehwDSP9RPn3GbMeB_WaRP0Ovzaca1g9ff3o-tRDHj_niFpNmjyDo')),
                '-----BEGIN PUBLIC KEY-----'.PHP_EOL.
                'MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEV5aWVKiV7Hx2IezAodbrPsqa2szK'.PHP_EOL.
                'kyco1l6HANI/1E+fcZsx4H9ZpE/Q6/NpxrWD19/ej61EMeP+eIWk2aPIOg=='.PHP_EOL.
                '-----END PUBLIC KEY-----'.PHP_EOL
            )
        );

        static::assertTrue($response->isValid($request->reveal(), 180));
    }

    /**
     * @test
     */
    public function theChallengeInTheRequestDoesNotMatchTheChallengeInTheClientData()
    {
        $response = SignatureResponse::create(
            $this->getValidSignatureResponse()
        );

        $request = $this->prophesize(SignatureRequest::class);
        $request->getChallenge()->willReturn('foo');
        $request->getApplicationId()->willReturn('https://twofactors:4043');
        $request->hasRegisteredKey(Argument::type(KeyHandler::class))->willReturn(true);
        $request->getRegisteredKey(Argument::type(KeyHandler::class))->willReturn(
            RegisteredKey::create(
                'U2F_V2',
                KeyHandler::create(Base64Url::decode('Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ')),
                PublicKey::create(Base64Url::decode('BFeWllSolex8diHswKHW6z7KmtrMypMnKNZehwDSP9RPn3GbMeB_WaRP0Ovzaca1g9ff3o-tRDHj_niFpNmjyDo')),
                '-----BEGIN PUBLIC KEY-----'.PHP_EOL.
                'MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEV5aWVKiV7Hx2IezAodbrPsqa2szK'.PHP_EOL.
                'kyco1l6HANI/1E+fcZsx4H9ZpE/Q6/NpxrWD19/ej61EMeP+eIWk2aPIOg=='.PHP_EOL.
                '-----END PUBLIC KEY-----'.PHP_EOL
            )
        );

        static::assertFalse($response->isValid($request->reveal(), 180));
    }

    /**
     * @test
     */
    public function theApplicationIdInTheRequestDoesNotMatchTheApplicationIdInTheClientData()
    {
        $response = SignatureResponse::create(
            $this->getValidSignatureResponse()
        );

        $request = $this->prophesize(SignatureRequest::class);
        $request->getChallenge()->willReturn(Base64Url::decode('F-zksRh5thzKyZR6O0Fr7QxlZ-xEX9_mNH8H3cHn_Po'));
        $request->getApplicationId()->willReturn('https://no-factors:443');
        $request->hasRegisteredKey(Argument::type(KeyHandler::class))->willReturn(true);
        $request->getRegisteredKey(Argument::type(KeyHandler::class))->willReturn(
            RegisteredKey::create(
                'U2F_V2',
                KeyHandler::create(Base64Url::decode('Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ')),
                PublicKey::create(Base64Url::decode('BFeWllSolex8diHswKHW6z7KmtrMypMnKNZehwDSP9RPn3GbMeB_WaRP0Ovzaca1g9ff3o-tRDHj_niFpNmjyDo')),
                '-----BEGIN PUBLIC KEY-----'.PHP_EOL.
                'MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEV5aWVKiV7Hx2IezAodbrPsqa2szK'.PHP_EOL.
                'kyco1l6HANI/1E+fcZsx4H9ZpE/Q6/NpxrWD19/ej61EMeP+eIWk2aPIOg=='.PHP_EOL.
                '-----END PUBLIC KEY-----'.PHP_EOL
            )
        );

        static::assertFalse($response->isValid($request->reveal(), 180));
    }

    /**
     * @test
     */
    public function theCounterIsInvalid()
    {
        $response = SignatureResponse::create(
            $this->getValidSignatureResponse()
        );

        $request = $this->prophesize(SignatureRequest::class);
        $request->getChallenge()->willReturn(Base64Url::decode('F-zksRh5thzKyZR6O0Fr7QxlZ-xEX9_mNH8H3cHn_Po'));
        $request->getApplicationId()->willReturn('https://twofactors:4043');
        $request->hasRegisteredKey(Argument::type(KeyHandler::class))->willReturn(true);
        $request->getRegisteredKey(Argument::type(KeyHandler::class))->willReturn(
            RegisteredKey::create(
                'U2F_V2',
                KeyHandler::create(Base64Url::decode('Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ')),
                PublicKey::create(Base64Url::decode('BFeWllSolex8diHswKHW6z7KmtrMypMnKNZehwDSP9RPn3GbMeB_WaRP0Ovzaca1g9ff3o-tRDHj_niFpNmjyDo')),
                '-----BEGIN PUBLIC KEY-----'.PHP_EOL.
                'MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEV5aWVKiV7Hx2IezAodbrPsqa2szK'.PHP_EOL.
                'kyco1l6HANI/1E+fcZsx4H9ZpE/Q6/NpxrWD19/ej61EMeP+eIWk2aPIOg=='.PHP_EOL.
                '-----END PUBLIC KEY-----'.PHP_EOL
            )
        );

        static::assertFalse($response->isValid($request->reveal(), 250));
    }

    private function getValidSignatureResponse(): array
    {
        return [
            'keyHandle' => 'Ws1pyRaocwNNxYIXIHttjOO1628kVQ2EK6EVVZ_wWKs089-rszT2fkSnSfm4V6wV9ryz2-K8Vm5Fs_r7ctAcoQ',
            'clientData' => 'eyJ0eXAiOiJuYXZpZ2F0b3IuaWQuZ2V0QXNzZXJ0aW9uIiwiY2hhbGxlbmdlIjoiRi16a3NSaDV0aHpLeVpSNk8wRnI3UXhsWi14RVg5X21OSDhIM2NIbl9QbyIsIm9yaWdpbiI6Imh0dHBzOi8vdHdvZmFjdG9yczo0MDQzIiwiY2lkX3B1YmtleSI6InVudXNlZCJ9',
            'signatureData' => 'AQAAALowRQIgU-oyzSNitffUGZgRSEijbBytbz8ZwxZvnKSVC90oAm8CIQDoMW5ZtwUooptNB5M-2W_jSjT0yNOkWnU_w1e9aj7vMA',
        ];
    }
}
