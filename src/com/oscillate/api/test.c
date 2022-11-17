// Online C compiler to run C program online
#include <stdio.h>

int calculatedist(int x,int y)
{
  int distance =0;
  int dist = y-x;
  distance = dist5;

  return distance;
}
float calc( int dist,int n )
{
  float answer=0;
if(dist<=5)
answer=n10;
else if(dist<=10)
answer=n20;
else if(dist<=15)
answer=n30;
else if(dist<=20)
answer=n40;
else
answer=n50;



  if(dist>10 && n>5)
  {
answer =  answer-(0.10*answer);
    }




return answer;

}

int inde(char a)
{
  char arr[8]={'A','B','C','D','E','F','G','H'};
int i;int ind=0;
for(i=0;i<8;i++)
{
  if(a== arr[i]) {
ind=i;
break;}
}
return ind;
}





int main(void)
{
  char s, d;float ans=0;
  int n=0;int distance=0;
  printf("enter source location\n ");
  scanf(" %c", &s);
printf("enter destination location\n ");
  scanf(" %c", &d);
  printf("enter number of tickets  \n ");
  scanf(" %d", &n);

  int first=inde(s);
    int last=inde(d);

    if(((first>=0)&&(first<=7))&&((last>=0)&&(last<=7)) )
  distance =  calculatedist(first,last);
  else
  printf("invalid input");

  ans=calc(distance,n);


  printf("total charges : %d \n", ans);

  }