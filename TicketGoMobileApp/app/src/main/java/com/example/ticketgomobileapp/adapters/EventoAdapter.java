package com.example.ticketgomobileapp.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.ticketgomobileapp.R;
import com.example.ticketgomobileapp.models.Evento;
import java.util.List;

public class EventoAdapter extends RecyclerView.Adapter<EventoAdapter.EventoViewHolder> {

    private final List<Evento> eventoList;

    public EventoAdapter(List<Evento> eventoList) {
        this.eventoList = eventoList;
    }

    @NonNull
    @Override
    public EventoViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_evento, parent, false);
        return new EventoViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull EventoViewHolder holder, int position) {
        Evento evento = eventoList.get(position);

        // Bind dos dados do modelo
        holder.eventTitle.setText(evento.getTitulo());
        holder.eventDescription.setText(evento.getDescricao());
        holder.eventDateRange.setText(evento.getDatainicio() + " a " + evento.getDatafim());

    }

    @Override
    public int getItemCount() {
        return eventoList.size();
    }

    public static class EventoViewHolder extends RecyclerView.ViewHolder {
        ImageView eventImage;
        TextView eventTitle, eventDescription, eventDateRange;

        public EventoViewHolder(@NonNull View itemView) {
            super(itemView);
            eventImage = itemView.findViewById(R.id.eventImage);
            eventTitle = itemView.findViewById(R.id.eventTitle);
            eventDescription = itemView.findViewById(R.id.eventDescription);
            eventDateRange = itemView.findViewById(R.id.eventDateRange);
        }
    }
    public void updateListaEventos(List<Evento> novaLista) {
        this.eventoList.clear();
        this.eventoList.addAll(novaLista);
        notifyDataSetChanged();
    }
}
