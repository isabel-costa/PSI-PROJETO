package com.example.ticketgomobileapp;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.example.ticketgomobileapp.adaptadores.EventosAdapter;
import com.example.ticketgomobileapp.modelos.EventoBDHelper;
import com.example.ticketgomobileapp.modelos.Evento;
import com.example.ticketgomobileapp.modelos.Favorito;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class FavoritosActivity extends AppCompatActivity {

    private RecyclerView favoritosRecyclerView;
    private EventosAdapter eventosAdapter;
    private List<Evento> favoritoList;
    private EventoBDHelper eventoBDHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_favoritos);


        //Verificar se o utilizador está autenticado
        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        boolean isAuthenticated = sharedPreferences.getBoolean("is_authenticated", false);
        if (!isAuthenticated) {
            Intent intent = new Intent(FavoritosActivity.this, LoginActivity.class);
            startActivity(intent);
        }

        setContentView(R.layout.activity_favoritos);

        //Verfifica a conex
        if (!isOnline()) {
            Toast.makeText(this, "Você está offline. Apenas a lista de favoritos está disponível.", Toast.LENGTH_SHORT).show();
        }

        favoritosRecyclerView = findViewById(R.id.favoritosRecyclerView);

        // Configurar RecyclerView
        favoritosRecyclerView.setLayoutManager(new LinearLayoutManager(this));
        favoritoList = new ArrayList<>();
        eventosAdapter = new EventosAdapter(favoritoList);
        favoritosRecyclerView.setAdapter(eventosAdapter);

        // Inicializar banco de dados
        eventoBDHelper = new EventoBDHelper(this);

        // Carregar favoritos
        loadFavoritosFromDatabase();

        // Ícones de navegação
        ImageView houseIconView = findViewById(R.id.houseIconView);
        ImageView heartIconView = findViewById(R.id.heartIconView);
        ImageView profileIconView = findViewById(R.id.profileIconView);
        ImageView cartIconView = findViewById(R.id.cartIconView);




        // Ícone da casa - sempre redireciona para a MainActivity
        houseIconView.setOnClickListener(v -> {
            Intent intent = new Intent(FavoritosActivity.this, MainActivity.class);
            startActivity(intent);
        });

        // Ícone do coração - redireciona para Favoritos
        heartIconView.setOnClickListener(v -> {
            // Já estamos em Favoritos, então não faz nada
        });

        // Ícone do perfil
        profileIconView.setOnClickListener(v -> {
            SharedPreferences sharedPreferences1 = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
            boolean isAuthenticated1 = sharedPreferences1.getBoolean("is_authenticated", false);
            if (isAuthenticated1) {
                Intent intent = new Intent(FavoritosActivity.this, PerfilActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(FavoritosActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });

        cartIconView.setOnClickListener(v -> {
            SharedPreferences sharedPreferences1 = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
            boolean isAuthenticated1 = sharedPreferences1.getBoolean("is_authenticated", false);
            if (isAuthenticated1) {
                Intent intent = new Intent(FavoritosActivity.this, CarrinhoActivity.class);
                startActivity(intent);
            } else {
                Intent intent = new Intent(FavoritosActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });
    }

    private void carregarFavoritosDaAPI() {
        String url = "https://10.2.2.0/TicketGo/backend/web/api/favorito/";  // Substitua pela URL correta da sua API

        // Criar uma requisição para pegar os favoritos
        JsonArrayRequest request = new JsonArrayRequest(Request.Method.GET, url, null, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                List<Favorito> favoritos = new ArrayList<>();

                try {
                    // Parse dos dados da resposta
                    for (int i = 0; i < response.length(); i++) {
                        JSONObject jsonObject = response.getJSONObject(i);

                        Favorito favorito = new Favorito();
                        favorito.setId(jsonObject.getInt("id"));
                        favorito.setProfileId(jsonObject.getInt("profile_id"));
                        favorito.setEventoId(jsonObject.getInt("evento_id"));

                        favoritos.add(favorito);
                    }

                    // Salvar no banco local
                    EventoBDHelper eventoBDHelper = new EventoBDHelper(FavoritosActivity.this);
                    //eventoBDHelper.guardarFavoritosBD(favoritos);

                    // Atualizar a UI
                    favoritoList.clear();
                    //favoritoList.addAll(favoritos);
                    eventosAdapter.notifyDataSetChanged();

                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(FavoritosActivity.this, "Erro ao carregar favoritos", Toast.LENGTH_SHORT).show();
            }
        });

        // Adicionar a requisição na fila de requisições
        Volley.newRequestQueue(this).add(request);
    }

    /*private void sincronizarFavoritos() {
        // Iterar sobre os favoritos locais e enviar para a API
        for (Favorito favorito : favoritoList) {
            String url = "10.2.2.0/TicketGo/backend/web/api/favorito";

            // Criar o JSON que será enviado
            JSONObject jsonObject = new JSONObject();
            try {
                jsonObject.put("profile_id", favorito.getProfileId());
                jsonObject.put("evento_id", favorito.getEventoId());

                // Criar requisição POST com o JSON
                JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, url, jsonObject,
                        new Response.Listener<JSONObject>() {
                            @Override
                            public void onResponse(JSONObject response) {
                                // Sucesso, você pode adicionar lógica aqui caso precise
                                Log.d("Sincronizar", "Favorito sincronizado com sucesso");
                            }
                        },
                        new Response.ErrorListener() {
                            @Override
                            public void onErrorResponse(VolleyError error) {
                                Log.d("Sincronizar", "Erro ao sincronizar favorito");
                            }
                        });

                // Adicionar a requisição na fila de requisições
                Volley.newRequestQueue(this).add(request);

            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }*/

    private void loadFavoritosFromDatabase() {
        favoritoList.clear();
        //favoritoList.addAll(eventoBDHelper.getAllFavoritosBD());
        eventosAdapter.notifyDataSetChanged();

        if (favoritoList.isEmpty()) {
            Toast.makeText(this, "Nenhum favorito encontrado.", Toast.LENGTH_SHORT).show();
        }
    }

    private boolean isOnline() {
        ConnectivityManager connectivityManager =
                (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetwork = connectivityManager.getActiveNetworkInfo();
        return activeNetwork != null && activeNetwork.isConnected();
    }
}
