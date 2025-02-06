package com.example.ticketgomobileapp;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import com.google.android.material.textfield.TextInputEditText;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {

    private static final String LOGIN_URL = "http://10.0.2.2/SIS-PROJETO/TicketGo/backend/web/api/auth/login"; // Use o IP correto

    private TextInputEditText usernameEditText;
    private TextInputEditText passwordEditText;
    private Button loginButton;
    private TextView createAccountTextView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // Inicializar as views
        usernameEditText = findViewById(R.id.usernameEditText);
        passwordEditText = findViewById(R.id.passwordEditText);
        loginButton = findViewById(R.id.loginButton);
        createAccountTextView = findViewById(R.id.createAccountTextView);

        // Definir click listener para o botão de login
        loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String username = usernameEditText.getText().toString().trim();
                String password = passwordEditText.getText().toString().trim();

                if (username.isEmpty() || password.isEmpty()) {
                    Toast.makeText(LoginActivity.this, "Por favor, preencha todos os campos.", Toast.LENGTH_SHORT).show();
                } else {
                    authenticateUser (username, password);
                }
            }
        });

        createAccountTextView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(LoginActivity.this, RegistoActivity.class);
                startActivity(intent);
            }
        });
    }

    private void authenticateUser (String username, String password) {
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        Log.d("LoginActivity", "Iniciando autenticação para: " + username); // Log do username

        StringRequest stringRequest = new StringRequest(Request.Method.POST, LOGIN_URL,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("LoginActivity", "Resposta da API: " + response); // Log da resposta da API
                        try {
                            // Analisar a resposta em JSON
                            JSONObject jsonObject = new JSONObject(response);

                            // Verifique se a resposta contém o campo "auth_key"
                            if (jsonObject.has("auth_key")) {
                                String authKey = jsonObject.getString("auth_key");
                                String message = jsonObject.getString("message");

                                // Salvar o token no SharedPreferences
                                SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
                                SharedPreferences.Editor editor = sharedPreferences.edit();
                                editor.putString("auth_key", authKey);  // Salvar o token
                                editor.putBoolean("is_authenticated", true); // Atualizar o estado de autenticação
                                editor.apply();
                                Log.d("LoginActivity", "Token de autenticação: " + authKey); // Log do token de autenticação
                                Toast.makeText(LoginActivity.this, "Login bem-sucedido! " + message, Toast.LENGTH_SHORT).show();

                                // Navegar para a MainActivity
                                Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                                startActivity(intent);
                                finish(); // Finalizar a LoginActivity para que o usuário não volte para ela
                            } else {
                                Toast.makeText(LoginActivity.this, "Erro: Resposta inesperada do servidor.", Toast.LENGTH_SHORT).show();
                            }
                        } catch (JSONException e) {
                            Log.e("LoginActivity", "Erro ao analisar a resposta: " + e.getMessage());
                            Toast.makeText(LoginActivity.this, "Erro ao processar a resposta do servidor.", Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("LoginActivity", "Falha na requisição API: " + error.getMessage());
                        if (error.networkResponse != null) {
                            Log.e("LoginActivity", "Código de erro: " + error.networkResponse.statusCode);
                        }
                        Toast.makeText(LoginActivity.this, "Falha na conexão. Verifique sua internet.", Toast.LENGTH_SHORT).show();
                    }
                }) {
            @Override
            protected Map<String, String> getParams() {
                // Enviar o username e a password como parâmetros no método POST
                Map<String, String> params = new HashMap<>();
                params.put("username", username);
                params.put("password", password);
                return params;
            }
        };

        // Adicionar o pedido à fila de requisições
        requestQueue.add(stringRequest);
    }

    // Método para recuperar o token de autenticação
    private String getAuthKey() {
        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        return sharedPreferences.getString("auth_key", null);
    }
}