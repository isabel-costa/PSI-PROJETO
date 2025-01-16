package com.example.ticketgomobileapp.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.ticketgomobileapp.models.Evento;
import com.example.ticketgomobileapp.models.Local;
import com.example.ticketgomobileapp.R;

import java.util.List;

public class EventosAdapter extends RecyclerView.Adapter<EventosAdapter.EventoViewHolder> {
    private List<Evento> listaEventos;
    private List<Local> listaLocais; // Lista de locais

    public EventosAdapter(List<Evento> listaEventos, List<Local> listaLocais) {
        this.listaEventos = listaEventos;
        this.listaLocais = listaLocais;
    }

    @NonNull
    @Override
    public EventoViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_evento, parent, false);
        return new EventoViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(@NonNull EventoViewHolder holder, int position) {
        Evento evento = listaEventos.get(position);

        // Preencher o título do evento
        holder.titulo.setText(evento.getTitulo());

        // Preencher o local do evento
        String lugar = getLugarById(evento.getLocalId());
        holder.lugar.setText(lugar);

        // Preencher a data e hora de início do evento
        holder.eventDateTime1.setText(evento.getDatainicio());
    }

    // Método para obter o nome do lugar pelo ID
    private String getLugarById(int localId) {
        for (Local local : listaLocais) {
            if (local.getId() == localId) {
                return local.getLugar(); // Retorna o nome do lugar
            }
        }
        return "Local desconhecido"; // Retorna um valor padrão se não encontrar
    }

    @Override
    public int getItemCount() {
        return listaEventos.size();
    }

    public static class EventoViewHolder extends RecyclerView.ViewHolder {
        public TextView titulo;
        public TextView lugar;
        public TextView eventDateTime1;

        public EventoViewHolder (View itemView) {
            super(itemView);
            titulo = itemView.findViewById(R.id.titulo);
            lugar = itemView.findViewById(R.id.lugar);
            eventDateTime1 = itemView.findViewById(R.id.data);
        }
    }
}