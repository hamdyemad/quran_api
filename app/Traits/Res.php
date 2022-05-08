<?php

namespace App\Traits;

trait Res
{
  public function sendRes($message, $status = true,  $data = [])
  {
    return response()->json([
      'status' => $status,
      'message' => $message,
      'data' => $data
    ]);
  }

  protected function respondWithToken($token, $status = true, $message = '', $data = [])
  {
      return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() . ' minutes',
            'status' => $status,
            'message' => $message,
            'data' => $data
      ]);
  }
}
