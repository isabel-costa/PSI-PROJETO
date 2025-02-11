package com.example.ticketgomobileapp;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.ticketgomobileapp.utils.AuthUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class PerfilActivity extends AppCompatActivity {

    private TextView nameTextView;
    private TextView emailTextView;
    private TextView dataNascimentoTextView;
    private TextView nifTextView;
    private TextView moradaTextView;
    private Button logoutButton, editarPerfilButton; // Botão de logout
    private static final String PROFILE_URL = "http://10.0.2.2/TicketGo/backend/web/api/profile/get-profile";

    @SuppressLint("MissingInflatedId")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_perfil);

        ImageView houseIconView = findViewById(R.id.iconHome);
        ImageView heartIconView = findViewById(R.id.iconHeart);
        ImageView profileIconView = findViewById(R.id.iconProfile);
        ImageView cartIconView = findViewById(R.id.iconCart);

        logoutButton=findViewById(R.id.logoutButton);
        editarPerfilButton=findViewById(R.id.editProfileButton);

        // Verificar autenticação
        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        boolean isAuthenticated = (sharedPreferences.getBoolean("is_authenticated", false));
        String token = (sharedPreferences.getString("auth_key", ""));
        String userId = sharedPreferences.getString("user_id", "");
        String profileId=sharedPreferences.getString("profile_id","");
        Log.d("PerfilActivity", userId);
        Log.d("PerfilActivity",token);

        if (!isAuthenticated) {
            // Redirecionar para login se não autenticado
            Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
            return;
        }



        // Inicializar componentes
        nameTextView = findViewById(R.id.profileName);
        nifTextView = findViewById(R.id.txtNif); // Adicionei o ID correto
        dataNascimentoTextView = findViewById(R.id.txtDataNascimento);
        moradaTextView = findViewById(R.id.txtMorada);

        editarPerfilButton.setOnClickListener(v -> {
            RequestQueue requestQueue = Volley.newRequestQueue(this);

            // Create a JSON object with the data to send
            JSONObject params = new JSONObject();
            try {
                params.put("datanascimento", dataNascimentoTextView.getText().toString());
                params.put("nif", nifTextView.getText().toString());
                params.put("morada", moradaTextView.getText().toString());
            } catch (JSONException e) {
                e.printStackTrace();
            }

            String url = "http://10.0.2.2/TicketGo/backend/web/api/profile/" + profileId;

            JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(
                    Request.Method.PUT,
                    url,
                    params, // Request body (the data)
                    new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {
                            Log.d("PerfilActivity", response.toString());
                            Toast.makeText(PerfilActivity.this,"Informações atualizadas com sucesso!",Toast.LENGTH_SHORT).show();
                        }
                    },
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            // Handle error
                            Log.e("PerfilActivity", "Error: " + error.getMessage());
                        }
                    }) {
                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    // Optionally, you can add headers if needed (like Content-Type, Authorization, etc.)
                    Map<String, String> headers = new HashMap<>();
                    headers.put("Content-Type", "application/json"); // Ensure content type is set to JSON
                    return headers;
                }
            };

            requestQueue.add(jsonObjectRequest);
        });


        // Configurar botão de logout
        logoutButton.setOnClickListener(v -> {
            // Realizar o logout (remover dados de autenticação)
            SharedPreferences.Editor editor = sharedPreferences.edit();
            editor.putBoolean("is_authenticated", false);
            editor.commit();

            // Redirecionar para a tela de login
            Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
        });

        // Carregar dados do perfil
        houseIconView.setOnClickListener(v -> {
            Intent intent = new Intent(PerfilActivity.this, MainActivity.class);
            startActivity(intent);
        });

        heartIconView.setOnClickListener(v -> {
            if (AuthUtils.isUserAuthenticated(this)) {
                Intent intent = new Intent(PerfilActivity.this, FavoritosActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });

        profileIconView.setOnClickListener(v -> {
        });

        cartIconView.setOnClickListener(v -> {
            if (AuthUtils.isUserAuthenticated(this)) {
                Intent intent = new Intent(PerfilActivity.this, CarrinhoActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(PerfilActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });
        carregarDadosPerfil(userId,token);
    }

    private void carregarDadosPerfil(String userId, String token) {
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        // Ensure the URL is correct with GET method and parameters
        String urlWithParams = PROFILE_URL + "?profile_id=" + userId + "&token=" + token;

        StringRequest stringRequest = new StringRequest(Request.Method.GET, urlWithParams,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("PerfilActivity", "Resposta da API: " + response);
                        try {
                            JSONObject jsonObject = new JSONObject(response);

                            // Extract the 'profile' object from the response
                            JSONObject profileObject = jsonObject.getJSONObject("profile");

                            // Extract the details from the 'profile' object
                            String id= profileObject.getString("id");
                            String name = profileObject.getString("nome");
                            String dateOfBirth = profileObject.getString("datanascimento");
                            String nif = profileObject.getString("nif");
                            String address = profileObject.getString("morada");


                            nameTextView.setText(name);
                            dataNascimentoTextView.setText(dateOfBirth);
                            nifTextView.setText(nif);
                            moradaTextView.setText(address);

                            SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
                            SharedPreferences.Editor editor = sharedPreferences.edit();
                            editor.putString("profile_id", id);
                            editor.commit();


                        } catch (JSONException e) {
                            Log.e("PerfilActivity", "Erro ao analisar a resposta: " + e.getMessage());
                            Toast.makeText(PerfilActivity.this, "Erro ao processar a resposta do servidor.", Toast.LENGTH_SHORT).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.e("PerfilActivity", "Falha na requisição API: " + error.getMessage());
                        if (error.networkResponse != null) {
                            Log.e("PerfilActivity", "Código de erro: " + error.networkResponse.statusCode);
                        }
                        Toast.makeText(PerfilActivity.this, "Falha na conexão. Verifique a sua internet.", Toast.LENGTH_SHORT).show();
                    }
                });


        requestQueue.add(stringRequest);
    }
}