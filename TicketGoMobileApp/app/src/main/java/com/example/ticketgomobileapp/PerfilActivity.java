package com.example.ticketgomobileapp;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class PerfilActivity extends AppCompatActivity {

    private TextView nameTextView;
    private TextView emailTextView;
    private TextView dataNascimentoTextView;
    private TextView nifTextView;
    private TextView moradaTextView;
    private Button logoutButton; // Botão de logout
    private static final String PROFILE_URL = "http://10.0.2.2/TicketGoAPI/backend/web/profile"; // Substitua pelo endpoint da sua API

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_perfil);

        // Verificar autenticação
        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        boolean isAuthenticated = (sharedPreferences.getBoolean("is_authenticated", false));
        String authenticated = (sharedPreferences.getString("auth_token", ""));
        Log.d("PerfilActivity", authenticated);

        if (!isAuthenticated) {
            // Redirecionar para login se não autenticado
            Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
            return;
        }

        // Inicializar componentes
        nameTextView = findViewById(R.id.profileName);
        emailTextView = findViewById(R.id.profileEmail);
        nifTextView = findViewById(R.id.nifTextView); // Adicionei o ID correto

        // Configurar botão de logout
        /*logoutButton.setOnClickListener(v -> {
            // Realizar o logout (remover dados de autenticação)
            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.putBoolean("is_authenticated", false);
            editor.commit();

            // Redirecionar para a tela de login
            Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
        });*/

        // Carregar dados do perfil
        carregarDadosPerfil();
    }

    private void carregarDadosPerfil() {
        // Implementar lógica para carregar dados do perfil da API
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(
                Request.Method.GET,
                PROFILE_URL,
                null,
                response -> {
                    try {
                        String name = response.getString("name");
                        String email = response.getString("email");
                        String dataNascimento = response.getString("dataNascimento");
                        String nif = response.getString("nif");
                        String morada = response.getString("morada");

                        // Preencher os TextViews com os dados do usuário
                        nameTextView.setText(name);
                        emailTextView.setText(email);
                        dataNascimentoTextView.setText(dataNascimento);
                        nifTextView.setText(nif);
                        moradaTextView.setText(morada);
                    } catch (JSONException e) {
                        e.printStackTrace();
                        Toast.makeText(PerfilActivity.this, "Erro ao carregar dados do perfil", Toast.LENGTH_SHORT).show();
                    }
                },
                error -> Toast.makeText(PerfilActivity.this, "Erro ao carregar dados do perfil", Toast.LENGTH_SHORT).show()
        );
        requestQueue.add(jsonObjectRequest);
    }
}