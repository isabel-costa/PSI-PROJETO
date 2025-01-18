package com.example.ticketgomobileapp;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.SearchView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.example.ticketgomobileapp.adaptadores.EventosAdapter;
import com.example.ticketgomobileapp.modelos.Evento;
import com.example.ticketgomobileapp.modelos.Singleton;
import com.example.ticketgomobileapp.utils.AuthUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class MainActivity extends AppCompatActivity {

    private ImageView logoImageView;
    private SearchView searchView;
    private RecyclerView eventosRecyclerView;
    private EventosAdapter eventosAdapter;
    private List<Evento> eventoList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        logoImageView = findViewById(R.id.logoImageView);
        searchView = findViewById(R.id.searchView);
        eventosRecyclerView = findViewById(R.id.eventsRecyclerView);
        ImageView houseIconView = findViewById(R.id.houseIconView);
        ImageView heartIconView = findViewById(R.id.heartIconView);
        ImageView profileIconView = findViewById(R.id.profileIconView);

        // Configurar RecyclerView
        eventosRecyclerView.setLayoutManager(new LinearLayoutManager(this));
        eventoList = new ArrayList<>();
        eventosAdapter = new EventosAdapter(eventoList);
        eventosRecyclerView.setAdapter(eventosAdapter);

        // Carregar eventos da API
        loadEventosFromAPI();

        // Configurar SearchView
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                searchEventos(query);
                return false;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                searchEventos(newText);
                return false;
            }
        });

        // Configurar ícones de navegação
        houseIconView.setOnClickListener(v -> {
            Intent intent = new Intent(MainActivity.this, MainActivity.class);
            startActivity(intent);
        });

        heartIconView.setOnClickListener(v -> {
            if (AuthUtils.isUserAuthenticated(this)) {
                // Redireciona para Favoritos
                Intent intent = new Intent(MainActivity.this, FavoritosActivity.class);
                startActivity(intent);
            } else {
                // Redireciona para Login
                Intent intent = new Intent(MainActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });

        profileIconView.setOnClickListener(v -> {
            if (AuthUtils.isUserAuthenticated(this)) {
                // Redireciona para Perfil
                Intent intent = new Intent(MainActivity.this, PerfilActivity.class);
                startActivity(intent);
            } else {
                // Redireciona para Login
                Intent intent = new Intent(MainActivity.this, LoginActivity.class);
                startActivity(intent);
            }
        });
    }

    private void loadEventosFromAPI() {
        com.example.ticketgomobileapp.modelos.Singleton.getInstance(this).verEventos(this, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {
                eventoList.clear();
                try {
                    JSONArray eventosArray = response.getJSONArray("eventos");
                    for (int i = 0; i < eventosArray.length(); i++) {
                        JSONObject eventoObject = eventosArray.getJSONObject(i);
                        int id = eventoObject.getInt("id");
                        String titulo = eventoObject.getString("titulo");
                        String descricao = eventoObject.getString("descricao");
                        String dataInicio = eventoObject.getString("datainicio");
                        String dataFim = eventoObject.getString("datafim");
                        String localName = eventoObject.getString("nome_local");
                        String categoriaName = eventoObject.getString("nome_categoria");

                        eventoList.add(new Evento(id, titulo, descricao, dataInicio, dataFim, localName, categoriaName));
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                eventosAdapter.notifyDataSetChanged();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, "Erro ao carregar eventos", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void searchEventos(String query) {
        Singleton.getInstance(this).pesquisarEventos(this, query, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {
                eventoList.clear();
                try {
                    JSONArray eventosArray = response.getJSONArray("eventos");
                    for (int i = 0; i < eventosArray.length(); i++) {
                        JSONObject eventoObject = eventosArray.getJSONObject(i);
                        int id = eventoObject.getInt("id");
                        String titulo = eventoObject.getString("titulo");
                        String descricao = eventoObject.getString("descricao");
                        String dataInicio = eventoObject.getString("datainicio");
                        String dataFim = eventoObject.getString("datafim");
                        String localName = eventoObject.getString("nome_local");
                        String categoriaName = eventoObject.getString("nome_categoria");

                        eventoList.add(new Evento(id, titulo, descricao, dataInicio, dataFim, localName, categoriaName));
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                eventosAdapter.notifyDataSetChanged();
                if (eventoList.isEmpty()) {
                    Toast.makeText(MainActivity.this, "Nenhum evento encontrado", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(MainActivity.this, "Erro ao pesquisar eventos", Toast.LENGTH_SHORT).show();
            }
        });
    }
}
