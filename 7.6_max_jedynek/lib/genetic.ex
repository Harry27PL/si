defmodule Genetic do
  import Genetic.Math

  @p_cross 0.5
  @p_mutation 0.02

  def cross(ch1, ch2) do

    if (Enum.count ch1) != (Enum.count ch2) do
      raise "chromosomy muszą być tej samej długości"
    end

    length = Enum.count ch1

    if (getRandomFloat < @p_cross) do

      crossPoint = round(getRandomFloat * (length - 2) + 1)

      IO.puts crossPoint

      {chn1a, chn2b} = Enum.split ch1, crossPoint
      {chn2a, chn1b} = Enum.split ch2, crossPoint

      {chn1a ++ chn1b, chn2a ++ chn2b}

    else

      {ch1, ch2}

    end

  end

  def mutate(ch) do

    Enum.map(ch, fn(x) ->
      if (getRandomFloat < @p_mutation) do
        if x == 0, do: 1, else: 0
      else
        x
      end
    end)

  end

end
