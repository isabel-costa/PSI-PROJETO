package com.example.ticketgomobileapp;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.ticketgomobileapp.adaptadores.EventosAdapter;
import com.example.ticketgomobileapp.modelos.EventoBDHelper;
import com.example.ticketgomobileapp.modelos.Evento;

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


        // Verificar se o user esta autenticado
        SharedPreferences sharedPreferences = getSharedPreferences("MyAppPrefs", MODE_PRIVATE);
        boolean isAuthenticated = sharedPreferences.getBoolean("is_authenticated", false);
        if (!isAuthenticated) {
            Intent intent = new Intent(FavoritosActivity.this, LoginActivity.class);
            startActivity(intent);
        }

        setContentView(R.layout.activity_favoritos);

        // Verificar conexão online e informar ao usuário
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
    }

    private void loadFavoritosFromDatabase() {
        favoritoList.clear();
        favoritoList.addAll(eventoBDHelper.getAllFavoritosBD());
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
