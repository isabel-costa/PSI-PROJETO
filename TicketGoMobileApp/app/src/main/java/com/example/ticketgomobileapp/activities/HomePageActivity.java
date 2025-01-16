package com.example.ticketgomobileapp.activities;

import android.os.Bundle;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.RecyclerView;

import com.example.ticketgomobileapp.R;
import com.example.ticketgomobileapp.adapters.EventosAdapter;
import com.example.ticketgomobileapp.listeners.EventosListener;
import com.example.ticketgomobileapp.models.Evento;
import com.example.ticketgomobileapp.models.Local;
import com.example.ticketgomobileapp.network.SingletonGestorEventos;

import java.util.ArrayList;
import java.util.List;

public class HomePageActivity extends AppCompatActivity implements EventosListener {

    private EventosAdapter eventosAdapter;  // Updated variable name
    private RecyclerView recyclerView;
    private List<Evento> listaEventos;
    private List<Local> listaLocais;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_homepage);

        listaEventos = new ArrayList<>();
        listaLocais = new ArrayList<>();

        // Carregar eventos e locais
        SingletonGestorEventos.getInstance(this).carregarEventosELocaisAPI(this);
    }


    @Override
    public void onRefreshListaEventos(List<Evento> listaEventos, List<Local> listaLocais) {
        this.listaEventos = listaEventos;
        this.listaLocais = listaLocais;
    }

    @Override
    public void onError(String error) {
        // Display an error message to the user
        Toast.makeText(this, "Erro: " + error, Toast.LENGTH_SHORT).show();
    }
}