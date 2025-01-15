package com.example.ticketgomobileapp.activities;

import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.example.ticketgomobileapp.R;
import com.example.ticketgomobileapp.models.Evento;
import com.example.ticketgomobileapp.adapters.EventoAdapter;
import com.example.ticketgomobileapp.listeners.EventosListener;
import com.example.ticketgomobileapp.network.SingletonGestorEventos;
import java.util.ArrayList;

public class HomepageActivity extends AppCompatActivity implements EventosListener {
    private RecyclerView recyclerViewEventos;
    private EventoAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_homepage);

        recyclerViewEventos = findViewById(R.id.recyclerViewEventos);
        recyclerViewEventos.setLayoutManager(new LinearLayoutManager(this));

        adapter = new EventoAdapter(new ArrayList<>());
        recyclerViewEventos.setAdapter(adapter);

        SingletonGestorEventos.getInstance(this).setEventosListener(this);
        SingletonGestorEventos.getInstance(this).carregarEventosAPI(this, this);
    }

    @Override
    public void onRefreshListaEventos(ArrayList<Evento> listaEventos) {
        adapter.updateListaEventos(listaEventos);
    }
}
